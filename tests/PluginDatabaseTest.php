<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use Illuminate\Support\Facades\DB;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class PluginDatabaseTest extends TestCase
{
    public function test_table()
    {

        // Test our sample table was created
        DB::table('test')->insert([
            'value' => 'banana',
            'expiration' => 1203,
        ]);

        $rows = DB::table('test')->get()->toArray();
        $rows[0] = (array) $rows[0];

        // All worked?
        $this->assertEquals([
            [
                'id' => 1,
                'value' => 'banana',
                'expiration' => 1203,
            ],
        ], $rows);

    }

    public function test_boot_plugin()
    {

        config()->set('plugins.active', ['one']);
        LaravelPlugins::bootPlugins();
        $this->assertEquals(true, constant('ONE_PLUGIN_LOADED'));
        config()->set('plugins.active', []);

    }

    public function test_activate_plugin()
    {

        LaravelPlugins::activatePlugin('one');

        // Test our sample table was updated
        DB::table('test')->insert([
            'value' => 'banana',
            'expiration' => 1203,
        ]);
        DB::table('test')->insert([
            'value' => 'fruit',
            'expiration' => 69,
            'extra_data' => 'yes',
        ]);

        $rows = DB::table('test')->get()->toArray();
        $rows[0] = (array) $rows[0];
        $rows[1] = (array) $rows[1];

        // All worked?
        $this->assertEquals([
            [
                'id' => 1,
                'value' => 'banana',
                'expiration' => 1203,
                'extra_data' => 'default',
            ],
            [
                'id' => 2,
                'value' => 'fruit',
                'expiration' => 69,
                'extra_data' => 'yes',
            ],
        ], $rows);

    }
}
