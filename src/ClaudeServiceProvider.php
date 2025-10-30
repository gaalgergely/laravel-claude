<?php

namespace GergelyGaal\LaravelClaude;

use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use GergelyGaal\LaravelClaude\Services\Claude as ClaudeService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ClaudeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-claude')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(
            ClaudeClientContract::class,
            HttpClaudeClient::class
        );

        $this->app->singleton(ClaudeService::class, function ($app) {
            return new ClaudeService($app->make(ClaudeClientContract::class));
        });
    }

    /**
     * @note without Spatie
     */
    /*public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/feature-flags.php', 'feature-flags');

        $this->app->singleton(FeatureFlagsManager::class, function ($app) {
            return new FeatureFlagsManager(config('feature-flags'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/feature-flags.php' => config_path('feature-flags.php'),
        ], 'feature-flags-config');
    }*/
}
