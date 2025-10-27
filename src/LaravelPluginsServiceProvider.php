<?php

namespace InvisibleDragon\LaravelPlugins;

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
}
