<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;
use InvisibleDragon\LaravelPlugins\LPClassLoader;

class ClassLoaderTest extends TestCase {

    public function test_find_class() {

        // See if it can find our plugin's DemoController file exactly
        $this->assertEquals(
            dirname(__FILE__) . '/test_plugins/one/Controllers/DemoController.php',
            LPClassLoader::findClass( 'Plugins\one\Controllers\DemoController' )
        );

    }

}
