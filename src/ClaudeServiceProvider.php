<?php

namespace Gaalgergely\LaravelClaude;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ClaudeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-claude')
            ->hasConfigFile()
            ->hasCommand(\Gaalgergely\LaravelClaude\Commands\GenerateArticleCommand::class);
    }

    public function registeringPackage(): void
    {
        $this->app->bind(
            \Gaalgergely\LaravelClaude\Contracts\ClaudeClientContract::class,
            \Gaalgergely\LaravelClaude\Clients\HttpClaudeClient::class
        );
    }
}
