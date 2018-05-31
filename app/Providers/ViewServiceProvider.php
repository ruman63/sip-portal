<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $lastUpdatedNav = \DB::table('schemes')->max('nav_date');
            return $view->with(
                'lastUpdatedNav', 
                $lastUpdatedNav ? Carbon::parse($lastUpdatedNav) : null
            );
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
