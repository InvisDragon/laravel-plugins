<?php

namespace InvisibleDragon\LaravelPlugins\Middleware;

use Closure;
use Illuminate\Http\Request;
use InvisibleDragon\LaravelPlugins\LaravelPlugins;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;

/**
 * Checks if an associated plugin with a route is activated
 *
 * This should be moved to a drop-in package at some point
 */
class PluginActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $plugin): Response
    {
        $active_plugins = LaravelPlugins::getActivePlugins();
        if(!in_array($plugin, $active_plugins)) {
            throw new LockedHttpException('Associated plugin not active on this instance');
        }
        return $next($request);
    }
}
