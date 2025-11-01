<?php

namespace InvisibleDragon\LaravelPlugins;

use Illuminate\Support\Facades\Route;
use InvisibleDragon\LaravelPlugins\Middleware\PluginActiveMiddleware;

class LaravelPlugins {

    /**
     * Returns all plugin directories for the current application
     */
    public static function getPluginDirectories() {
        return config('plugins.plugin_dirs');
    }

    public static function getAllPluginDirectories() : array {
        $information = [];
        foreach(static::getPluginDirectories() as $pluginDirectory) {
            $cdir = scandir($pluginDirectory);
            foreach ($cdir as $value) {
                if (!in_array($value,array(".",".."))) {
                    $dir = $pluginDirectory . $value;
                    if (is_dir($dir)) {
                        $information[ $value ] = $dir;
                    }
                }
            }
        }
        return $information;
    }

    /**
     * Fetches all of the valid plugin information available in a plugin-key -> data structure
     */
    public static function getAllPluginInformation() : array {
        $information = [];
        foreach(static::getAllPluginDirectories() as $key => $dir) {
            $information[ $key ] = json_decode(
                file_get_contents( $dir . DIRECTORY_SEPARATOR . 'plugin.json' ),
                true
            );
        }
        return $information;
    }

    /**
     * Fetches which plugins are available
     */
    public static function getActivePlugins() : array {
        if(function_exists('tenant')) { // multi-tenant support
            return tenant()->active_plugins ?: [];
        } else {
            return config( 'plugins.active', [] );
        }
    }

    /**
     * Add all routes for plugins available to the platform
     */
    public static function addPluginRoutes($group = 'web') {

        foreach(static::getPluginDirectories() as $pluginDirectory) {
            foreach(glob( $pluginDirectory . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . $group . '.php' ) as $routeFile) {
                $pluginName = explode( DIRECTORY_SEPARATOR, $routeFile );
                $pluginName = $pluginName[ count($pluginName) - 3 ];
                Route::middleware([ PluginActiveMiddleware::class . ':' . $pluginName ])->group(function() use ($routeFile) {
                    require( $routeFile );
                });
            }
        }

    }

}
