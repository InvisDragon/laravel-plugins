<?php

namespace InvisibleDragon\LaravelPlugins;

use Illuminate\Support\Facades\Event;
use InvisibleDragon\LaravelPlugins\Listeners\BootListener;
use InvisibleDragon\LaravelPlugins\Listeners\TenantMigrated;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPluginsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-plugins')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_plugins_table');
    }

    public function packageBooted()
    {
        LPClassLoader::setup();

        // Tenanted based application listeners
        if (function_exists('tenant')) {
            Event::listen(
                'Stancl\Tenancy\Events\TenancyInitialized',
                BootListener::class
            );
            Event::listen(
                'Stancl\Tenancy\Events\DatabaseMigrated',
                TenantMigrated::class
            );
        } else {
            LaravelPlugins::bootPlugins();
        }
    }
}
