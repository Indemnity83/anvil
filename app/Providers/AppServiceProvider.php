<?php

/** @noinspection ALL */

namespace App\Providers;

use App\Site;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use PDOException;

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
    public function boot()
    {
        try {
            Site::all()->each(function (Site $site) {
                $this->app['config']["filesystems.disks.{$site->disk}"] = [
                    'driver' => 'local',
                    'root' => storage_path("sites/{$site->name}"),
                ];
            });
        } catch (PDOException $exception) {
            // If the application hasn't been migrated yet, this error will be thrown
            // so ... let just ignore it for now :D
        }
    }
}
