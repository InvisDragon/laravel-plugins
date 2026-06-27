<?php

use Illuminate\Support\Facades\Route;
use Plugins\one\Controllers\DemoController;

Route::get('/plugin/one', function () {
    return response()->json(['status' => 'ok']);
});

Route::resource('/plugin/controller', DemoController::class);
