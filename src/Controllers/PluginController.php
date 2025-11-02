<?php

namespace InvisibleDragon\LaravelPlugins\Controllers;

use Illuminate\Http\Request;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

/**
 * This controller allows for listing and turning on/off plugins from a UI
 * mostly for tenanted applications
 *
 * Note: you should protect this with superuser permissions
 */
class PluginController {

    public function getData() {
        $items = LaravelPlugins::getAllPluginInformation();
        $activePlugins = LaravelPlugins::getActivePlugins();
        foreach($items as $key => &$item) {
            $item['key'] = $key;
            $item['active'] = in_array( $key, $activePlugins );
        }
        return array_values($items);
    }

    public function index() {
        return response()->json([
            'data' => $this->getData(),
        ]);
    }

    public function update(Request $request, string $pluginName) {
        if($request->post('activate')) {
            LaravelPlugins::activatePlugin( $pluginName );
            return response([ 'status' => 'ok' ]);
        } else {
            return response([ 'message' => 'Invalid Request' ], 400);
        }
    }

}
