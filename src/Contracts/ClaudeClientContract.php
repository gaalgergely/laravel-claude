<?php

namespace Gaalgergely\LaravelClaude\Contracts;

interface ClaudeClientContract
{
    public function generate(string $prompt): string;
}
