<?php

use Plugins\one\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

Route::get( '/plugin/one', function() {
    return response()->json([ 'status' => 'ok' ]);
} );

Route::resource('/plugin/controller', DemoController::class);
