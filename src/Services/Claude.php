<?php

namespace GergelyGaal\LaravelClaude\Services;

use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;

class Claude
{
    public function __construct(private ClaudeClientContract $client) {}

    public function sendMessages(array $messages): array
    {
        return $this->client->sendMessages($messages);
    }

    public function countMessageTokens(array $messages): array
    {
        return $this->client->countMessageTokens($messages);
    }

    public function listModels(?string $afterId = null, ?string $beforeId = null, ?int $limit = null): array
    {
        return $this->client->listModels($afterId, $beforeId, $limit);
    }

    public function getModel(string $model): array
    {
        return $this->client->getModel($model);
    }

    public function createMessageBatch(array $messageBatch): array
    {
        return $this->client->createMessageBatch($messageBatch);
    }

    public function retrieveMessageBatch(string $messageBatchId): array
    {
        return $this->client->retrieveMessageBatch($messageBatchId);
    }

    public function retrieveMessageBatchResults(string $messageBatchId): array
    {
        return $this->client->retrieveMessageBatchResults($messageBatchId);
    }

    public function listMessageBatches(?string $afterId = null, ?string $beforeId = null, ?int $limit = null): array
    {
        return $this->client->listMessageBatches($afterId, $beforeId, $limit);
    }

    public function cancelMessageBatch(string $messageBatchId): array
    {
        return $this->client->cancelMessageBatch($messageBatchId);
    }

    public function deleteMessageBatch(string $messageBatchId): array
    {
        return $this->client->deleteMessageBatch($messageBatchId);
    }

    public function createFile(array $file) : array
    {
        return $this->client->createFile($file);
    }

    public function listFiles(?string $afterId = null, ?string $beforeId = null, ?int $limit = null): array
    {
        return $this->client->listFiles($afterId, $beforeId, $limit);
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
