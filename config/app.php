<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Laravel'),



    'env' => env('APP_ENV', 'production'),


    'debug' => (bool) env('APP_DEBUG', false),


    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),


    'timezone' => 'America/Sao_Paulo',


    'locale' => 'ru_RU',


    'fallback_locale' => 'en',


    'faker_locale' => 'en_US',


    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',


    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],


    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
        Spatie\Permission\PermissionServiceProvider::class,

        \App\Providers\FilamentServiceProvider::class,
        LaravelLegends\PtBrValidator\ValidatorProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),


    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Helper' => \App\Helpers\Core::class,
    ])->toArray(),

];
