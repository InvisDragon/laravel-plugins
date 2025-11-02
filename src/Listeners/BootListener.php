<?php

namespace InvisibleDragon\LaravelPlugins\Listeners;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class BootListener {

    // This listener just boots our plugins when it's ready
    public function handle($event) {
        LaravelPlugins::bootPlugins();
    }

}
