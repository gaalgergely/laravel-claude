<?php

namespace GergelyGaal\LaravelClaude\Contracts;

interface ClaudeClientContract
{
    public function sendMessages(string $prompt): array;

    public function countMessageTokens(string $prompt): array;

    public function listModels() :array;

    public function getModel(string $model) :array;

    public function createFile() : array;

    public function listFiles() :array;

    public function getFileMetadata(string $fileId) :array;
}
