<?php

namespace App\Providers;

use App\Models\Parametre;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
     View::composer('*', function ($view) {
        $view->with('parametres', Parametre::first());
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Gate::define('employer', function (User $user) {
            return $user->hasRole('employer');
        });

        Gate::after(function (User $user) {
           return $user->hasRole("administrateur");
        });
    }
}
