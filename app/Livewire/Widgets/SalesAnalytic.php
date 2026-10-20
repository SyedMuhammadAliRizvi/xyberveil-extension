<?php

namespace App\Livewire\Widgets;

use App\Models\LocalSubscription;
use App\Models\Plan;
use Carbon\Carbon;
use Livewire\Component;

class SalesAnalytic extends Component
{
    public $totalSalesPercent;

    public $weekSales;

    public $monthSales;

    public $yearSales;

    public $boughtPlans = [];

    public $topPlans = [];

    public function mount()
    {
        $this->calculateSalesOverview();
        $this->fetchTopPlans();

        $this->topBoughtPlans();
    }

    public function calculateSalesOverview()
    {
        $statuses = ['trialing', 'active', 'grace', 'cancelled', 'expired'];
        $this->weekSales = LocalSubscription::whereBetween('created_at', [now()->startOfWeek(), now()])
            ->whereIn('status', $statuses)
            ->sum('amount_paid');
        $this->monthSales = LocalSubscription::whereMonth('created_at', now()->month)
            ->whereIn('status', $statuses)
            ->sum('amount_paid');
        $this->yearSales = LocalSubscription::whereYear('created_at', now()->year)
            ->whereIn('status', $statuses)
            ->sum('amount_paid');

        // For radial chart: % of goal (you can change the 1000 to your target)
        $target = 10000;
        $this->totalSalesPercent = min(100, round(($this->monthSales / $target) * 100));
    }

    public function fetchTopPlans()
    {
        $statuses = ['trialing', 'active', 'grace', 'cancelled', 'expired'];
        $plans = LocalSubscription::selectRaw('plan_id, COUNT(*) as total')
            ->whereIn('status', $statuses)
            ->groupBy('plan_id')
            ->orderByDesc('total')
            ->take(4)
            ->get();
        // dd($plans);

        foreach ($plans as $p) {
            $plan = Plan::find($p->plan_id);
            if ($plan) {
                $this->topPlans[] = [
                    'label' => $plan->name,
                    'value' => $p->total,
                ];
            }
        }
    }

    public function topBoughtPlans()
    {
        $this->boughtPlans = Plan::select('plans.id', 'plans.name', 'plans.discount_price as price')
            ->join('local_subscriptions', 'local_subscriptions.plan_id', '=', 'plans.id')
            ->where('local_subscriptions.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('plans.id', 'plans.name', 'plans.discount_price')
            ->selectRaw('COUNT(local_subscriptions.id) as total_sales, SUM(local_subscriptions.amount_paid) as total_revenue')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.widgets.sales-analytic');
    }
}
