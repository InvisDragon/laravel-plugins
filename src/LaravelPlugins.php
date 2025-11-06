<?php

namespace InvisibleDragon\LaravelPlugins;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelPlugins\Middleware\PluginActiveMiddleware;

class LaravelPlugins
{
    /**
     * Returns all plugin directories for the current application
     */
    public static function getPluginDirectories()
    {
        return config('plugins.plugin_dirs');
    }

    public static function getAllPluginDirectories(): array
    {
        $information = [];
        foreach (static::getPluginDirectories() as $pluginDirectory) {
            if (! is_dir($pluginDirectory)) {
                continue;
            }
            $cdir = scandir($pluginDirectory);
            foreach ($cdir as $value) {
                if (! in_array($value, ['.', '..'])) {
                    $dir = $pluginDirectory.DIRECTORY_SEPARATOR.$value;
                    if (is_dir($dir)) {
                        $information[$value] = $dir;
                    }
                }
            }
        }

        return $information;
    }

    /**
     * Fetches all of the valid plugin information available in a plugin-key -> data structure
     */
    public static function getAllPluginInformation(): array
    {
        $information = [];
        foreach (static::getAllPluginDirectories() as $key => $dir) {
            $information[$key] = json_decode(
                file_get_contents($dir.DIRECTORY_SEPARATOR.'plugin.json'),
                true
            );
            $information[$key]['dir'] = $dir;
        }

        return $information;
    }

    /**
     * Fetches which plugins are available
     */
    public static function getActivePlugins(): array
    {
        if (function_exists('tenant')) { // multi-tenant support
            return tenant()->active_plugins ?: [];
        } else {
            return config('plugins.active', []);
        }
    }

    protected static function setActivePlugins($plugins)
    {
        if (function_exists('tenant')) { // multi-tenant support
            $tenant = tenant();
            $tenant->active_plugins = $plugins;
            $tenant->save();
        }
    }

    /**
     * Add all routes for plugins available to the platform
     */
    public static function addPluginRoutes($group = 'web')
    {

        foreach (static::getPluginDirectories() as $pluginDirectory) {
            foreach (glob($pluginDirectory.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.$group.'.php') as $routeFile) {
                $pluginName = explode(DIRECTORY_SEPARATOR, $routeFile);
                $pluginName = $pluginName[count($pluginName) - 3];
                Route::middleware([PluginActiveMiddleware::class.':'.$pluginName])->group(function () use ($routeFile) {
                    require $routeFile;
                });
            }
        }

    }

    public static function activatePlugin($plugin)
    {
        $active_plugins = static::getActivePlugins();
        $active_plugins[] = $plugin;

        // Run migrations
        static::migrateForPlugin($plugin);

        static::setActivePlugins($active_plugins);

    }

    public static function migrateForPlugin($plugin)
    {
        $dirs = static::getAllPluginDirectories();

        // Get migrator default parameters
        $resolver = app('db');
        $files = app('files');
        $dispatcher = app('events');
        $repository = new LPMigrationRepository($resolver, $plugin);
        if (! $repository->repositoryExists()) {
            $repository->createRepository();
        }

        /** @var Migrator */
        $migrator = new Migrator($repository, $resolver, $files, $dispatcher);
        $migrator->run([
            $dirs[$plugin].DIRECTORY_SEPARATOR.'migrations',
        ]);
    }

    public static function bootPlugins()
    {
        $active_plugins = static::getActivePlugins();
        foreach ($active_plugins as $active_plugin) {
            static::bootPlugin($active_plugin);
        }
    }

    public static function bootPlugin($active_plugin)
    {
        $plugins = static::getAllPluginDirectories();
        $file = @$plugins[$active_plugin].DIRECTORY_SEPARATOR.'plugin.php';
        if (file_exists($file)) {
            include $file;
        }
    }
}
