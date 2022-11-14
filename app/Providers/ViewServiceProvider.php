<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // View::composer('partials.header', function ($view) {
        //     $notifications = Auth::user()->unreadNotifications()->count();
        //     $view->with('notifications', $notifications);
        // });
        View::composer('*', function ($view) {
            $view->with('locale', app()->getLocale());
        });
    }
}
