<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserRoleProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $allHelperFiles = glob(app_path('Helpers').'/*.php');

        foreach ($allHelperFiles as $key => $helperFile) {
            require_once $helperFile;
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
