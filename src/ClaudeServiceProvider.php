<?php

namespace GergelyGaal\LaravelClaude;

use GergelyGaal\LaravelClaude\Clients\HttpClaudeClient;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use GergelyGaal\LaravelClaude\Exceptions\ApiKeyIsMissingException;
use GergelyGaal\LaravelClaude\Services\Claude as ClaudeService;
use Illuminate\Support\ServiceProvider;

class ClaudeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/claude.php', 'claude');

        $apiKey = config('claude.api_key');
        if (!is_string($apiKey)) {
            throw new ApiKeyIsMissingException('The Claude API Key is missing. Please set the CLAUDE_API_KEY env variable.');
        }

        $this->app->bind(
            ClaudeClientContract::class,
            HttpClaudeClient::class
        );

        $this->app->singleton(ClaudeService::class, function ($app) {
            return new ClaudeService($app->make(ClaudeClientContract::class));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/claude.php' => config_path('claude.php'),
        ], 'claude');
    }
}
