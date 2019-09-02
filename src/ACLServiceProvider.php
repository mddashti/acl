<?php
namespace Niyam\ACL;

use Illuminate\Support\ServiceProvider;

class ACLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        response()->macro('_json', function ($status, $message, $data = []) {
            return response()->json([
                'status'    => $status,
                'message'   => $message,
                'data'      => $data
            ]);
        });
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if(env('SSO_SERVER', false))
            $this->registerMigrations();
    }

    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__ . '/Database/Migration');
    }
}
