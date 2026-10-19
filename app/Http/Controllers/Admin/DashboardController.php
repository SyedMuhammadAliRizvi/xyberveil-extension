<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\FetchProxyUsersSummary;
use App\Models\LocalSubscription;
use App\Models\Plan;
use App\Models\ProxyServer;
use App\Models\ProxySession;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Every widget id the draggable dashboard grid currently knows about, in
     * default order. A saved layout is only trusted if it contains exactly
     * this set of ids, so adding/removing a widget here safely falls back
     * everyone to the new default instead of rendering a stale/broken order.
     *
     * @var list<string>
     */
    public const DASHBOARD_WIDGETS = [
        'kpi-live-vpn',
        'kpi-revenue',
        'kpi-total-users',
        'kpi-open-tickets',
        'vps-health-gauge',
        'users-map',
        'ranked-servers',
        'vpn-protocols',
        'device-platforms',
        'top-plans',
        'revenue-trend',
        'subscription-status',
        'vps-table',
        'recent-signups',
    ];

    /**
     * Widget width choices available in the custom/flat layout, keyed by
     * the value stored in dashboard_widget_sizes. Only meaningful once a
     * custom order exists — the default nested layout's sizes are fixed by
     * its hand-tuned pairing and aren't individually resizable.
     *
     * @var array<string, string>
     */
    public const WIDGET_SIZES = [
        'sm' => 'md:col-span-6 xl:col-span-3',
        'md' => 'md:col-span-6 xl:col-span-6',
        'lg' => 'md:col-span-12 xl:col-span-8',
        'full' => 'md:col-span-12 xl:col-span-12',
    ];

    /**
     * Starting size for each widget the first time a custom layout is
     * created (before the admin has resized anything themselves).
     *
     * @var array<string, string>
     */
    public const DEFAULT_WIDGET_SIZES = [
        'kpi-live-vpn' => 'md',
        'kpi-revenue' => 'md',
        'kpi-total-users' => 'md',
        'kpi-open-tickets' => 'md',
        'vps-health-gauge' => 'md',
        'users-map' => 'lg',
        'ranked-servers' => 'md',
        'vpn-protocols' => 'sm',
        'device-platforms' => 'sm',
        'top-plans' => 'sm',
        'revenue-trend' => 'lg',
        'subscription-status' => 'md',
        'vps-table' => 'lg',
        'recent-signups' => 'md',
    ];

    public function index(): View
    {
        $data = Cache::remember('admin_dashboard', 60, function () {
            $totalUsers = User::where('role', 'user')->count();
            $newUsersToday = User::where('role', 'user')->whereDate('created_at', today())->count();
            $bannedUsers = User::whereNotNull('banned_at')->count();

            $activeSubscriptions = LocalSubscription::whereIn('status', ['active', 'trialing', 'grace'])
                ->where('ends_at', '>', now())->count();

            $subscriptionsByStatus = LocalSubscription::selectRaw('status, count(*) as total')
                ->groupBy('status')->pluck('total', 'status')->toArray();

            $revenueThisMonth = (float) LocalSubscription::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->sum('amount_paid');

            $revenueLastMonth = (float) LocalSubscription::whereMonth('created_at', now()->subMonthNoOverflow()->month)
                ->whereYear('created_at', now()->subMonthNoOverflow()->year)->sum('amount_paid');

            $rawChart = LocalSubscription::selectRaw('DATE(created_at) as date, SUM(amount_paid) as total')
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->groupBy('date')->orderBy('date')->get()->keyBy('date');

            $revenueDates = [];
            $revenueValues = [];
            for ($i = 29; $i >= 0; $i--) {
                $d = now()->subDays($i)->toDateString();
                $revenueDates[] = now()->subDays($i)->format('M d');
                $revenueValues[] = (float) ($rawChart[$d]->total ?? 0);
            }

            $rawUsersTrend = User::where('role', 'user')
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw('DATE(created_at) as date, count(*) as total')
                ->groupBy('date')->pluck('total', 'date');

            $newUsersTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i)->toDateString();
                $newUsersTrend[] = (int) ($rawUsersTrend[$d] ?? 0);
            }

            $openTickets = Ticket::where('status', 'open')->count();

            $rawTicketsTrend = Ticket::where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw('DATE(created_at) as date, count(*) as total')
                ->groupBy('date')->pluck('total', 'date');

            $ticketsTrend = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = now()->subDays($i)->toDateString();
                $ticketsTrend[] = (int) ($rawTicketsTrend[$d] ?? 0);
            }

            $devicesByPlatform = UserDevice::whereNull('revoked_at')
                ->where('is_blocked', false)
                ->selectRaw('platform, count(*) as total')
                ->groupBy('platform')
                ->pluck('total', 'platform')
                ->toArray();

            $topPlansByRevenue = Plan::withSum(
                ['subscriptions as revenue' => fn ($q) => $q->where('local_subscriptions.created_at', '>=', now()->subDays(30))],
                'amount_paid'
            )->withCount(
                ['subscriptions as sub_count' => fn ($q) => $q->where('local_subscriptions.created_at', '>=', now()->subDays(30))]
            )->orderByDesc('revenue')->limit(5)->get(['id', 'name']);

            // Fleet-wide proxy server health + live session count per server, fully
            // DB-backed (no live agent HTTP calls) so this stays fast and cacheable.
            $liveSessionCounts = ProxySession::whereNull('ended_at')
                ->selectRaw('proxy_server_id, count(*) as total')
                ->groupBy('proxy_server_id')
                ->pluck('total', 'proxy_server_id');

            $proxyServers = ProxyServer::orderBy('name')
                ->get(['id', 'name', 'country', 'flag', 'ip', 'proxy_port', 'socks_port', 'setup_status', 'is_active'])
                ->map(function (ProxyServer $server) use ($liveSessionCounts) {
                    $server->live_sessions = (int) ($liveSessionCounts[$server->id] ?? 0);

                    return $server;
                })
                ->sortByDesc('live_sessions')
                ->values();

            $totalProxyServers = $proxyServers->count();
            $readyProxyServers = $proxyServers->where('is_active', true)->where('setup_status', 'ready')->count();
            $installingProxyServers = $proxyServers->where('setup_status', 'installing')->count();
            $failedProxyServers = $proxyServers->where('setup_status', 'failed')->count();
            $pendingProxyServers = $proxyServers->where('setup_status', 'pending')->count();
            $proxyHealthPct = $totalProxyServers > 0 ? round(($readyProxyServers / $totalProxyServers) * 100, 1) : 0.0;
            $subsAdoptionPct = $totalUsers > 0 ? round(($activeSubscriptions / $totalUsers) * 100, 1) : 0.0;

            $recentUsers = User::where('role', 'user')
                ->with('activeSubscription')
                ->latest()->limit(8)
                ->get(['id', 'name', 'email', 'created_at']);

            return compact(
                'totalUsers', 'newUsersToday', 'bannedUsers',
                'activeSubscriptions', 'subscriptionsByStatus', 'subsAdoptionPct',
                'revenueThisMonth', 'revenueLastMonth',
                'revenueDates', 'revenueValues',
                'openTickets', 'ticketsTrend', 'devicesByPlatform', 'topPlansByRevenue',
                'newUsersTrend', 'proxyServers', 'totalProxyServers', 'readyProxyServers',
                'installingProxyServers', 'failedProxyServers', 'pendingProxyServers',
                'proxyHealthPct', 'recentUsers'
            );
        });

        // FetchProxyUsersSummary normally keeps this warm every ~2 minutes via the
        // scheduler, but if nothing has populated it yet (e.g. scheduler not
        // running locally), fetch it synchronously once rather than showing
        // zeros indefinitely — same fallback OnlineUsersMonitor uses.
        $cachedProxySummary = Cache::get('proxy_users_summary');
        if (empty($cachedProxySummary['fetched_at'])) {
            FetchProxyUsersSummary::dispatchSync();
            $cachedProxySummary = Cache::get('proxy_users_summary');
        }

        $proxyStats = $cachedProxySummary['stats'] ?? null;

        $proxySummary = [
            'users' => $proxyStats['users'] ?? ['total' => 0, 'active' => 0, 'pending' => 0, 'banned' => 0],
            'servers' => $proxyStats['servers'] ?? ['total' => 0, 'active' => 0],
            'sessions' => $proxyStats['sessions'] ?? ['live' => 0, 'total' => 0],
            'fetched_at' => $cachedProxySummary['fetched_at'] ?? null,
        ];

        // No per-session client geolocation exists for proxy sessions, so this is
        // an honest approximation: each server's live session count, weighted by
        // that server's own location (not the connecting client's).
        $countryActiveSessions = $data['proxyServers']
            ->filter(fn ($server) => $server->live_sessions > 0 && self::countryCode($server->country))
            ->groupBy(fn ($server) => self::countryCode($server->country))
            ->map(fn ($group) => $group->sum('live_sessions'));

        $user = Auth::user();
        $savedLayout = $user->dashboard_layout;
        $isCustomLayout = is_array($savedLayout) && ! array_diff(self::DASHBOARD_WIDGETS, $savedLayout) && ! array_diff($savedLayout, self::DASHBOARD_WIDGETS);
        $dashboardOrder = $isCustomLayout ? $savedLayout : self::DASHBOARD_WIDGETS;

        $savedSizes = $user->dashboard_widget_sizes;
        $widgetSizes = self::DEFAULT_WIDGET_SIZES;
        if (is_array($savedSizes)) {
            foreach ($savedSizes as $widgetId => $sizeKey) {
                if (isset($widgetSizes[$widgetId]) && array_key_exists($sizeKey, self::WIDGET_SIZES)) {
                    $widgetSizes[$widgetId] = $sizeKey;
                }
            }
        }

        return view('admin.dashboard', array_merge($data, [
            'proxySummary' => $proxySummary,
            'countryActiveSessions' => $countryActiveSessions,
            'dashboardOrder' => $dashboardOrder,
            'isCustomLayout' => $isCustomLayout,
            'widgetSizes' => $widgetSizes,
        ]));
    }

    /**
     * Persist the signed-in admin's custom dashboard widget order and
     * (optionally) per-widget sizes. Only ever called from the dashboard's
     * own drag-and-drop grid.
     */
    public function saveLayout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'widgets' => ['required', 'array', 'size:'.count(self::DASHBOARD_WIDGETS)],
            'widgets.*' => ['required', 'string', 'in:'.implode(',', self::DASHBOARD_WIDGETS)],
            'sizes' => ['sometimes', 'array'],
            'sizes.*' => ['string', 'in:'.implode(',', array_keys(self::WIDGET_SIZES))],
        ]);

        if (count(array_unique($validated['widgets'])) !== count(self::DASHBOARD_WIDGETS)) {
            return response()->json(['message' => 'Invalid widget layout.'], 422);
        }

        $update = ['dashboard_layout' => $validated['widgets']];

        if (array_key_exists('sizes', $validated)) {
            $sizes = array_intersect_key($validated['sizes'], self::DEFAULT_WIDGET_SIZES);
            $update['dashboard_widget_sizes'] = $sizes;
        }

        Auth::user()->update($update);

        return response()->json(['message' => 'Dashboard layout saved.']);
    }

    /**
     * Clear the signed-in admin's custom dashboard widget order and sizes,
     * restoring the default arrangement.
     */
    public function resetLayout(): JsonResponse
    {
        Auth::user()->update(['dashboard_layout' => null, 'dashboard_widget_sizes' => null]);

        return response()->json(['message' => 'Dashboard layout reset.']);
    }

    /**
     * Best-effort country-name → ISO 3166-1 alpha-2 lookup for the handful of
     * countries proxy servers are typically deployed in. Used only to plot the
     * dashboard's "Active Sessions by Server Location" map/flags — an unmapped
     * name simply isn't plotted, it's still counted everywhere else.
     */
    private static function countryCode(?string $name): ?string
    {
        static $map = [
            'united states' => 'US', 'usa' => 'US', 'united kingdom' => 'GB', 'uk' => 'GB',
            'germany' => 'DE', 'france' => 'FR', 'netherlands' => 'NL', 'poland' => 'PL',
            'canada' => 'CA', 'japan' => 'JP', 'singapore' => 'SG', 'australia' => 'AU',
            'spain' => 'ES', 'italy' => 'IT', 'sweden' => 'SE', 'switzerland' => 'CH',
            'norway' => 'NO', 'finland' => 'FI', 'denmark' => 'DK', 'belgium' => 'BE',
            'austria' => 'AT', 'ireland' => 'IE', 'portugal' => 'PT', 'romania' => 'RO',
            'bulgaria' => 'BG', 'czechia' => 'CZ', 'czech republic' => 'CZ', 'hungary' => 'HU',
            'ukraine' => 'UA', 'russia' => 'RU', 'turkey' => 'TR', 'greece' => 'GR',
            'india' => 'IN', 'china' => 'CN', 'hong kong' => 'HK', 'south korea' => 'KR',
            'korea' => 'KR', 'taiwan' => 'TW', 'indonesia' => 'ID', 'malaysia' => 'MY',
            'thailand' => 'TH', 'vietnam' => 'VN', 'philippines' => 'PH', 'israel' => 'IL',
            'united arab emirates' => 'AE', 'uae' => 'AE', 'saudi arabia' => 'SA',
            'south africa' => 'ZA', 'egypt' => 'EG', 'nigeria' => 'NG', 'kenya' => 'KE',
            'brazil' => 'BR', 'mexico' => 'MX', 'argentina' => 'AR', 'chile' => 'CL',
            'colombia' => 'CO', 'peru' => 'PE', 'new zealand' => 'NZ', 'iceland' => 'IS',
            'luxembourg' => 'LU', 'estonia' => 'EE', 'latvia' => 'LV', 'lithuania' => 'LT',
            'slovakia' => 'SK', 'slovenia' => 'SI', 'croatia' => 'HR', 'serbia' => 'RS',
            'moldova' => 'MD', 'cyprus' => 'CY', 'malta' => 'MT', 'pakistan' => 'PK',
            'bangladesh' => 'BD', 'sri lanka' => 'LK', 'kazakhstan' => 'KZ',
        ];

        if (! $name) {
            return null;
        }

        return $map[strtolower(trim($name))] ?? null;
    }
}
