<?php

namespace GergelyGaal\LaravelClaude\Contracts;

use GergelyGaal\LaravelClaude\DTOs\MessagesPayload;

interface ClaudeClientContract
{
    public function sendMessages(array $messages): array;

    public function countMessageTokens(string $prompt): array;

    public function listModels() :array;

    public function getModel(string $model) :array;

    public function createFile(array $file) : array;

    public function listFiles() :array;

    public function getFileMetadata(string $fileId) :array;

    public function downloadFile(string $fileId) :string;

    public function deleteFile(string $fileId) :array;
}
