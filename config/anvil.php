<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Output Path
    |--------------------------------------------------------------------------
    |
    | Where the generated Anvil package zip will be written.
    | You can override this in your app's config/anvil.php.
    |
    */

    'output' => env('ANVIL_OUTPUT', storage_path('app/anvil/anvil-package.zip')),

    /*
    |--------------------------------------------------------------------------
    | Excluded Paths
    |--------------------------------------------------------------------------
    |
    | These paths (relative to the project root) will not be included
    | in the generated zip.
    |
    */

    'exclude' => [
        '.git',
        'node_modules',
        'tests',
        'storage/app',
        'storage/logs',
        '.env',
        'vendor/bin',
        'docker',
        'apps',
        'scripts',
        '.github',
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer Options
    |--------------------------------------------------------------------------
    |
    | Defaults for composer install when building the package.
    |
    */

    'composer' => [
        'no_dev' => true,
        'optimize_autoloader' => true,
        'prefer_dist' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | NPM Options
    |--------------------------------------------------------------------------
    |
    | Command and script to run for building frontend assets.
    |
    */

    'npm' => [
        'enabled' => true,
        'binary' => env('ANVIL_NPM_BINARY', 'npm'),
        'build_script' => env('ANVIL_NPM_BUILD_SCRIPT', 'build'),
        'ci' => true, // run npm ci instead of npm install
    ],
];