<?php

namespace GergelyGaal\LaravelClaude\Contracts;

interface ClaudeClientContract
{
    public function generate(string $prompt): string;
}
