<?php

namespace InvisibleDragon\LaravelPlugins;

class LaravelPlugins {

    public static function getPluginDirectories() {
        return config('plugins.plugin_dirs');
    }

    public static function getAllPluginInformation() {
        $information = [];
        foreach(static::getPluginDirectories() as $pluginDirectory) {
            $cdir = scandir($pluginDirectory);
            foreach ($cdir as $value) {
                if (!in_array($value,array(".",".."))) {
                    $dir = $pluginDirectory . DIRECTORY_SEPARATOR . $value;
                    if (is_dir($dir)) {

                        $information[ $value ] = json_decode(
                            file_get_contents( $dir . DIRECTORY_SEPARATOR . 'plugin.json' ),
                            true
                        );

                    }
                }
            }
        }
        return $information;
    }

}
