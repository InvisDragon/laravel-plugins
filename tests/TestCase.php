<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use InvisibleDragon\LaravelPlugins\LaravelPluginsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'InvisibleDragon\\LaravelPlugins\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelPluginsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('plugins.plugin_dirs', [
            dirname(__FILE__).'/test_plugins',
        ]);

        foreach (File::allFiles(__DIR__.'//migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }
    }
}
