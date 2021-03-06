<?php

namespace Orchestra\View\Tests\Feature;

use Illuminate\View\ViewServiceProvider as BaseServiceProvider;
use Orchestra\Testbench\TestCase as Testbench;
use Orchestra\View\ViewServiceProvider as OverrideServiceProvider;

abstract class TestCase extends Testbench
{
    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Theme' => \Orchestra\Support\Facades\Theme::class,
        ];
    }

    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function overrideApplicationProviders($app): array
    {
        return [
            BaseServiceProvider::class => OverrideServiceProvider::class,
        ];
    }
}
