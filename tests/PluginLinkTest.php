<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class PluginLinkTest extends TestCase
{

    public function test_get_dirs()
    {
        $this->artisan('plugins:link-assets', [ '--verbose' => true, '--force' => true ])
            ->expectsOutputToContain('one: Linked')
            ->assertSuccessful();

        $this->assertStringContainsString(
            'Comic Sans',
            file_get_contents( public_path('plugins/one/style.css') ),
            'CSS file locatable in public directory');
    }

}
