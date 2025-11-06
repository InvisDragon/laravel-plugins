<?php

namespace InvisibleDragon\LaravelPlugins\Listeners;

use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class TenantMigrated
{
    public function handle($event)
    {
        // Ensure all of our plugins get migrated too!
        foreach (LaravelPlugins::getActivePlugins() as $plugin) {
            echo '> Migrate '.$plugin." for tenant\n";
            LaravelPlugins::migrateForPlugin($plugin);
        }
    }
}
