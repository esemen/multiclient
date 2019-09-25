<?php

namespace Esemen\MultiClient;

use Esemen\MultiClient\Client;
use Esemen\MultiClient\Domain;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider
{
    private $activeClient;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if (config('multiclient.active')) {

            // Define Host
            if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
                // Clear www.
                if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
                    $host = substr($_SERVER['HTTP_HOST'], 4);
                } else {
                    $host = $_SERVER['HTTP_HOST'];
                }
            } else {
                $host = 'localhost';
            }

            // Find Client from Domain
            try {
                $domain = Domain::where('domain', $host)->first();
                if ($domain) {
                    $this->activeClient = Client::find($domain->client_id);
                } else {
                    $this->activeClient = new Client();
                }
            } catch (\Exception $e) {
                $this->activeClient = new Client();
            }

            $this->app->singleton(Client::class, function () {
                return ($this->activeClient);
            });

            $this->app->singleton(('Client'), function () {
                return $this->activeClient;
            });

        }

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Config
        $this->mergeConfigFrom(__DIR__ . '/config/multiclient.php', 'multiclient');
        $this->publishes([
            __DIR__ . '/config/multiclient.php' => config_path('multiclient.php')
        ]);
    }
}