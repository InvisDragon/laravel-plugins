<?php

namespace Plugins\one\Controllers;

class DemoController
{
    public function index()
    {
        return response()->json(['status' => 'controller']);
    }
}
