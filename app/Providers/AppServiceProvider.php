<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory as ViewFactory;

class AppServiceProvider extends ServiceProvider
{   
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ViewFactory $view)
    {
        $view->composer('*', 'App\Http\ViewComposers\GlobalComposer');
    }
}
