<?php

namespace InvisibleDragon\LaravelPlugins\Commands;

use Illuminate\Console\Command;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;

/**
 * Based on the Laravel storage:link command, this links public assets from
 * plugins to the public directory
 */
class PluginLinkAssetsCommand extends Command
{
    public $signature = 'plugins:link-assets
        {--relative : Create the symbolic link using relative paths}
        {--force : Recreate existing symbolic links}';

    public $description = 'Link detected plugins\' public asset folders';

    public function handle(): int
    {
        $relative = $this->option('relative');

        $target = config('plugins.public_dir', public_path('plugins'));

        if (! file_exists($target)) {
            $this->laravel->make('files')->makeDirectory($target, 0755, true);
        }

        foreach (LaravelPlugins::getAllPluginInformation() as $pluginKey => $plugin) {

            $target_dir = $target.DIRECTORY_SEPARATOR.$pluginKey;

            if (file_exists($target_dir) && ! $this->isRemovableSymlink($target_dir, $this->option('force'))) {
                $this->error("$pluginKey: Link already exists.");

                continue;
            }

            $source_dir = $plugin['dir'].DIRECTORY_SEPARATOR.'public';
            if (! file_exists($source_dir)) {
                $this->warn("$pluginKey: No public directory to link", 'v');

                continue;
            }

            if (is_link($target_dir)) {
                $this->laravel->make('files')->delete($target_dir);
            }

            if ($relative) {
                $this->laravel->make('files')->relativeLink($source_dir, $target_dir);
            } else {
                $this->laravel->make('files')->link($source_dir, $target_dir);
            }

            $this->info("$pluginKey: Linked");
        }

        return self::SUCCESS;
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
