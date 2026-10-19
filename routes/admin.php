<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\AdminProfile;
use App\Livewire\Admin\AllAdmins;
use App\Livewire\Admin\AllBlockedIps;
use App\Livewire\Admin\AllDevices;
use App\Livewire\Admin\AllFeedbacks;
use App\Livewire\Admin\AllNotifications;
use App\Livewire\Admin\AllPlans;
use App\Livewire\Admin\AllPromoCodes;
use App\Livewire\Admin\AllPurchases;
use App\Livewire\Admin\AllServers;
use App\Livewire\Admin\AllTickets;
use App\Livewire\Admin\AllUsers;
use App\Livewire\Admin\Configuration;
use App\Livewire\Admin\ManageUser;
use App\Livewire\Admin\OnlineUsersMonitor;
use App\Livewire\Admin\ProxyManager;
use App\Livewire\Admin\ProxyServers;
use App\Livewire\Admin\ServerAdd;
use App\Livewire\Admin\ServerEdit;
use App\Livewire\Admin\TicketDetails;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/profile', AdminProfile::class)->name('admin.profile');
    Route::post('/admin/dashboard/layout', [DashboardController::class, 'saveLayout'])->name('admin.dashboard.layout.save');
    Route::post('/admin/dashboard/layout/reset', [DashboardController::class, 'resetLayout'])->name('admin.dashboard.layout.reset');

    Route::get('/online-users', OnlineUsersMonitor::class)->name('online-users.monitor');

    Route::redirect('/proxy-users', '/users')->name('proxy-users.all');
    Route::get('/proxy-user/{id}/manage', function (int $id) {
        $user = User::findOrFail($id);

        return redirect()->route('user.manage', ['user' => $user->slug, 'tab' => 'proxy']);
    })->name('proxy-user.manage');
    Route::get('/proxy-servers', ProxyServers::class)->name('proxy-servers.all');
    Route::get('/proxy-servers/{proxyServer}/manage', ProxyManager::class)->name('proxy-servers.manage');

    Route::get('/servers', AllServers::class)->name('servers.all');
    Route::get('/servers/create', ServerAdd::class)->name('servers.add');
    Route::get('/servers/{server}/edit', ServerEdit::class)->name('servers.edit');

    Route::get('/plans', AllPlans::class)->name('plans.all');

    Route::get('/promo-codes', AllPromoCodes::class)->name('promo-codes.all');

    Route::get('/transactions', AllPurchases::class)->name('transactions.all');

    Route::get('/users', AllUsers::class)->name('users.all');
    Route::get('/user/{user:slug}/manage', ManageUser::class)->name('user.manage');

    Route::get('/devices', AllDevices::class)->name('devices.all');
    Route::get('/blocked-ips', AllBlockedIps::class)->name('blocked-ips.all');

    Route::get('/admin-accounts', AllAdmins::class)->name('admins.all');

    Route::get('/tickets', AllTickets::class)->name('admin.tickets');
    Route::get('/tickets/{ticketId}', TicketDetails::class)->name('admin.ticket.details');

    Route::get('/notifications', AllNotifications::class)->name('admin.notifications');

    Route::get('/feedbacks', AllFeedbacks::class)->name('admin.feedbacks');

    Route::prefix('settings')->group(function () {
        Route::get('/config', Configuration::class)->name('all-config');
    });
});
