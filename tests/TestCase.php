<?php

namespace GaalGergely\LaravelClaude\Tests;

use GaalGergely\LaravelClaude\ClaudeServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('claude', require __DIR__.'/../config/claude.php');
        config()->set('claude.api_key', 'test-key'); // avoids ApiKeyIsMissingException
    }
}

