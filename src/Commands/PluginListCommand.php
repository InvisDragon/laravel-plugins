<?php

namespace InvisibleDragon\LaravelPlugins\Commands;

use Illuminate\Console\Command;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

class PluginListCommand extends Command
{
    public $signature = 'plugins:list';

    public $description = 'List Plugins';

    public function handle(): int
    {
        foreach( LaravelPlugins::getAllPluginInformation() as $plugin ) {
            $this->info( $plugin['name'] );
            if(isset($plugin['author'])) {
                $this->info( '> Author: ' . $plugin['author'], 'v' );
            }
            if(isset($plugin['description'])) {
                $this->info( '> Description: ' . $plugin['description'], 'v' );
            }
        }

        return self::SUCCESS;
    }
}
