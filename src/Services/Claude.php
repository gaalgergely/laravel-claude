<?php

namespace GergelyGaal\LaravelClaude\Services;

use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use GergelyGaal\LaravelClaude\DTOs\Message;
use GergelyGaal\LaravelClaude\DTOs\MessagesPayload;

/**
 * @todo Test Claude Facade ...
 */

class Claude
{
    public function __construct(private ClaudeClientContract $client) {}

    public function sendMessages(array $messages): array
    {
        return $this->client->sendMessages($messages);
    }

    public function countMessageTokens(string $topic): array
    {
        return $this->client->countMessageTokens($topic);
    }

    public function listModels(): array
    {
        return $this->client->listModels();
    }

    public function getModel(string $model): array
    {
        return $this->client->getModel($model);
    }

    public function createFile(array $file) : array
    {
        return $this->client->createFile($file);
    }

    public function listFiles(): array
    {
        return $this->client->listFiles();
    }

    public function getFileMetadata(string $fileId): array
    {
        return $this->client->getFileMetadata($fileId);
    }

    public function downloadFile(string $fileId): string
    {
        return $this->client->downloadFile($fileId);
    }

    public function deleteFile(string $fileId): array
    {
        return $this->client->deleteFile($fileId);
    }
}
