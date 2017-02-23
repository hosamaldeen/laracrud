<?php

namespace HossamAhmed\LaraCRUD;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LaraCRUDServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/HossamAhmed/laraCRUD'),
                ], 'public');
        $this->publishes([
            __DIR__ . '/config' => config_path(''),
                ]);
        
        //load migrations
        if(floatval(Application::VERSION) >= 5.3){
            $this->loadMigrationsFrom(__DIR__.'/migrations');
        }else{
            $this->publishes([
            __DIR__ . '/migrations' => database_path('migrations'),
                ]);
        }
        
        // load view
        $this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'laraCRUD');
        
        /*
         * loading routes and sometimes add middelware group
         */
        $router=$this->app->router;
        if(config('CRUD.middelware_group')!=''):
            $router->group([ 'middleware' => [config('CRUD.middelware_group')]], function($router) {
			require __DIR__ . '/Http/routes.php';
		});
        else:    
            include __DIR__.'/Http/routes.php';
        endif;   
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
