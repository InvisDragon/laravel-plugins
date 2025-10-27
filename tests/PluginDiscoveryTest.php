<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class PluginDiscoveryTest extends TestCase {

    public function test_get_dirs() {
        $this->assertEquals( [
            dirname(__FILE__) . '/test_plugins/',
        ], LaravelPlugins::getPluginDirectories() );
    }

    public function test_discover_plugins() {
        $this->assertEquals( [
            'one' => [
                'name' => 'One',
                'author' => 'Joe Simpson',
                'description' => 'This is a plugin',
            ]
        ], LaravelPlugins::getAllPluginInformation() );
    }

}
