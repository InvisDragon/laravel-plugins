<?php

namespace InvisibleDragon\LaravelPlugins;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use InvisibleDragon\LaravelPlugins\Commands\PluginLinkAssetsCommand;
use InvisibleDragon\LaravelPlugins\Commands\PluginListCommand;
use InvisibleDragon\LaravelPlugins\Hooks\Hook;
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
            ->hasCommands([
                PluginListCommand::class,
                PluginLinkAssetsCommand::class,
            ])
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

        // Blade
        Blade::directive( 'hook', function($hookName) {
            ob_start();
            Hook::call( $hookName );
            return ob_get_clean();
        } );

    }
}
