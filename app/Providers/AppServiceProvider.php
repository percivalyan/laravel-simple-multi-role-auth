<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;

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
    public function boot()
    {
        View::composer('layouts.navbar', function ($view) {
            $user = Auth::user();
            $logs = [];

            if ($user) {
                $logs = $user->role === 'admin'
                    ? Session::with('user')->orderByDesc('last_activity')->limit(5)->get()
                    : Session::where('user_id', $user->id)->orderByDesc('last_activity')->limit(5)->get();
            }

            $view->with('navbar_logs', $logs);
        });
    }
}
