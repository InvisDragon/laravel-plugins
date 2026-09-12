<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\Hooks\Hook;

class HookTest extends TestCase
{
    public static $called = false;

    public function test_basic_hooks()
    {

        Hook::add('basic_test', function () {
            static::$called = true;
        });

        Hook::call('basic_test');
        $this->assertTrue(static::$called, 'Hook called');

    }
}
