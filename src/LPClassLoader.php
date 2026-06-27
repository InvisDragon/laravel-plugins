<?php

namespace InvisibleDragon\LaravelPlugins;

/**
 * Loads classes from plugins, loosely based on Composer's ClassLoader
 */
class LPClassLoader
{
    /** @var \Closure(string):void */
    private static $includeFile;

    public static function setup()
    {

        static::initializeIncludeClosure();
        spl_autoload_register([static::class, 'loadClass']);

    }

    /**
     * Loads the given class or interface.
     *
     * @param  string  $class  The name of the class
     * @return true|null True if loaded, null otherwise
     */
    public static function loadClass($class)
    {
        if ($file = static::findClass($class)) {
            $includeFile = self::$includeFile;
            $includeFile($file);

            return true;
        }

        return null;
    }

    public static function findClass($cls)
    {

        if (str_starts_with($cls, 'Plugins\\')) {

            $pluginInfos = LaravelPlugins::getAllPluginDirectories();
            $namespaceBits = explode('\\', $cls);
            if (count($namespaceBits) < 2) {
                return false;
            }
            $plugin = $namespaceBits[1];
            if (! array_key_exists($plugin, $pluginInfos)) {
                return false;
            }
            $pluginDir = $pluginInfos[$plugin];

            $class = implode(DIRECTORY_SEPARATOR, array_splice($namespaceBits, 2));
            $shouldBe = $pluginDir.DIRECTORY_SEPARATOR.$class.'.php';

            if (is_file($shouldBe)) {
                return $shouldBe;
            }

        }

        return false;

    }

    /**
     * @return void
     */
    private static function initializeIncludeClosure()
    {
        if (self::$includeFile !== null) {
            return;
        }

        /**
         * Scope isolated include.
         *
         * Prevents access to $this/self from included files.
         *
         * @param  string  $file
         * @return void
         */
        self::$includeFile = \Closure::bind(static function ($file) {
            include $file;
        }, null, null);
    }
}
