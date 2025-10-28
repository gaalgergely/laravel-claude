<?php

namespace GergelyGaal\LaravelClaude\Contracts;

interface ClaudeClientContract
{
    public function sendMessages(string $prompt): array;

    public function countMessageTokens(string $prompt): array;

    public function listModels() :array;

    public function getModel(string $model) :array;
}
