<?php

namespace GergelyGaal\LaravelClaude\Services;

use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;

class Claude
{
    public function __construct(private ClaudeClientContract $client) {}

    public function sendMessages(string $topic): array
    {
        return $this->client->sendMessages($topic);
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

    public function createFile() : array
    {
        return $this->client->createFile();
    }

    public function listFiles(): array
    {
        return $this->client->listFiles();
    }

    public function getFileMetadata(string $fileId): array
    {
        return $this->client->getFileMetadata($fileId);
    }
}
