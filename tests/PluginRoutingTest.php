<?php

namespace InvisibleDragon\LaravelPlugins\Tests;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class PluginRoutingTest extends TestCase
{
    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function defineRoutes($router)
    {

        $router->get('/test', function () {
            return 'hello';
        });

        $router->prefix('api')->middleware('api')->group(function () {
            LaravelPlugins::addPluginRoutes('api');
        });

    }

    /*
     * Test if our plugin route does anything when our plugin is not active yet
     */
    public function test_inactive_plugin_route()
    {
        $resp = $this->get('/api/plugin/one');
        $resp->assertStatus(423);
    }

    /*
     * Test if our route works as expected when our plugin is activated
     */
    public function test_active_plugin_route()
    {

        // Force activate plugin
        config()->set('plugins.active', ['one']);

        $resp = $this->get('/api/plugin/one');
        $resp->assertStatus(200);
        $resp->assertExactJson([
            'status' => 'ok',
        ]);

    }

    /*
     * Test if our route works as expected when our plugin is activated
     */
    public function test_active_plugin_controller()
    {

        // Force activate plugin
        config()->set('plugins.active', ['one']);

        // This especially tests class loading
        $resp = $this->get('/api/plugin/controller');
        $resp->assertStatus(200);
        $resp->assertExactJson([
            'status' => 'controller',
        ]);

    }

    /*
     * Test if our test cases are working as expected
     */
    public function test_basic_routing()
    {
        $resp = $this->get('/test');
        $resp->assertStatus(200);
        $resp->assertContent('hello');
    }
}
