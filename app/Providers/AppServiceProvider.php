<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Podcast;
use App\Observers\PodcastObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Podcast::observe(PodcastObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
