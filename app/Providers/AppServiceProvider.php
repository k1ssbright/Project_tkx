<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

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
        Gate::define('access-admin', fn (User $user): bool => $user->is_admin);

        View::composer(
            ['layouts.app', 'layouts.admin', 'home', 'about', 'contact', 'partials.auth-visual'],
            function (ViewInstance $view): void {
                $view->with('siteImages', SiteSetting::imageUrls());
            },
        );
    }
}
