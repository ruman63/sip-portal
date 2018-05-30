<?php

namespace App\Providers;

use View;
use App\Scheme;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share(
            'lastUpdatedNav', 
            Carbon::parse( Scheme::min('nav_date'))
        );
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
