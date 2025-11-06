<?php

namespace InvisibleDragon\LaravelPlugins\Controllers;

use Illuminate\Http\Request;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

/**
 * This controller allows for listing and turning on/off plugins from a UI
 * mostly for tenanted applications
 *
 * Note: you should protect this with superuser permissions except the appScripts route
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

    /**
     * This returns plugin frontend scripts
     */
    public function appScripts() {
        $activePlugins = LaravelPlugins::getActivePlugins();
        $pluginInfo = LaravelPlugins::getAllPluginInformation();
        $output = '';
        foreach($activePlugins as $activePlugin) {
            $manifest = @$pluginInfo[ $activePlugin ]['scriptManifest'];
            if($manifest) {
                $manifest = json_decode(file_get_contents( $pluginInfo[$activePlugin]['dir'] . DIRECTORY_SEPARATOR . 'public'
                    . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'manifest.json' ), true);
                $file = 'src/frontend.js';
                if(@$manifest[ $file ]) {
                    $file = $pluginInfo[ $activePlugin ]['dir'] . DIRECTORY_SEPARATOR . 'public' .
                        DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . $manifest[$file]['file'];
                    if(file_exists( $file )) {
                        $output .= file_get_contents( $file );
                    }
                }
            } else {
                $file = $pluginInfo[ $activePlugin ]['dir'] . DIRECTORY_SEPARATOR . 'frontend.js';
                if(file_exists( $file )) {
                    $output .= file_get_contents( $file );
                }
            }
        }
        return response($output)->header('Content-Type', 'text/javascript');
    }

}
