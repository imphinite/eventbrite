<?php

namespace Eventbrite\ServiceProvider;

use Eventbrite\Eventbrite;
use Illuminate\Support\ServiceProvider;

class EventbriteServiceProvider extends ServiceProvider
{
    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
    

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
                __DIR__.'/../config/eventbrite.php' => config_path('eventbrite.php'),
            ], 'eventbrite');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Eventbrite', function()
        {
            return new Eventbrite;
        });
    }
        
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('Eventbrite');
    }
}
