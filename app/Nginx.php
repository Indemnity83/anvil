<?php

namespace App;

use Illuminate\Support\Facades\Log;

class Nginx
{
    public static function configPath($path)
    {
        return storage_path($path ? 'nginx'.DIRECTORY_SEPARATOR.$path : 'nginx');
    }

    public static function reload()
    {
        // TODO handle errors
        Log::info('Testing Nginx configuration');
        exec('nginx -t', $output, $exitCode);

        Log::info('Reloading Nginx');
        exec('nginx -s reload', $output, $exitCode);
    }
}
