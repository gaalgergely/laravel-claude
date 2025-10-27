<?php

namespace GergelyGaal\LaravelClaude;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ClaudeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-claude')
            ->hasConfigFile()
            ->hasCommand(\GergelyGaal\LaravelClaude\Commands\GenerateArticleCommand::class);
    }

    public function registeringPackage(): void
    {
        $this->app->bind(
            \GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract::class,
            \GergelyGaal\LaravelClaude\Clients\HttpClaudeClient::class
        );
    }
}
