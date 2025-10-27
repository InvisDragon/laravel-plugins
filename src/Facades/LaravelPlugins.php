<?php

namespace InvisibleDragon\LaravelPlugins\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Invisible Dragon\InvisibleDragon\LaravelPlugins\InvisibleDragon\LaravelPlugins
 */
class LaravelPlugins extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvisibleDragon\LaravelPlugins\LaravelPlugins::class;
    }
}
