<?php

namespace App\Providers;

use App\Models\SidebarGroup;
use App\Models\SidebarItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Facades\View::composer('components.app.sidebar', function ($view) {
            $user = Auth::user();
            $userPermissions = $user ? $user->getAllPermissions()->pluck('name') : collect();

            $groups = SidebarGroup::with(['items' => function ($query) use ($userPermissions) {
                $query->where('status', 1)
                    ->where(function ($q) use ($userPermissions) {
                        $q->whereNull('permission')
                            ->orWhereIn('permission', $userPermissions);
                    });
            }])
                ->where('status', 1)
                ->get()
                ->sortBy('id');

            $noGroupItems = SidebarItem::where('status', 1)
                ->whereNull('sidebar_group_id')
                ->where(function ($query) use ($userPermissions) {
                    $query->whereNull('permission')
                        ->orWhereIn('permission', $userPermissions);
                })
                ->get()
                ->sortBy('id');

            $view->with('groups', $groups)->with('unGrouped', $noGroupItems);
        });
    }
}
