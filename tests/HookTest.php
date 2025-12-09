<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\Hooks\Hook;

class HookTest extends TestCase
{
    public function test_basic_hooks()
    {

        Hook::add('test', function (&$called) {
            $called = true;
        });

        $called = false;
        Hook::call('test', [&$called]);
        $this->assertTrue($called, 'Hook called');

    }
}
