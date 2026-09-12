<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\Hooks\Hook;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class BladeTest extends TestCase
{
    public static $called = false;

    public function test_blade_hook()
    {

        Hook::add('blade_test', function () {
            echo 'ok!';
            static::$called = true;
        });

        $this->blade('@hook(blade_test)')->assertSee('ok!');

        $this->assertTrue(static::$called, 'Hook called');

    }

    public function test_blade_resource()
    {

        Hook::add('blade_resource', function () {
            LaravelPlugins::css('one', 'style.css');
            static::$called = true;
        });

        $this->blade('@hook(blade_resource)')->assertSee('plugins/one/style.css');

        $this->assertTrue(static::$called, 'Hook called');

    }
}
