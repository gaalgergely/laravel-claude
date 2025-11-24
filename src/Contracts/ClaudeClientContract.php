<?php

namespace GergelyGaal\LaravelClaude\Contracts;

interface ClaudeClientContract
{
    public function sendMessages(array $messages): array;

    public function countMessageTokens(array $messages): array;

    public function listModels(?string $afterId = null, ?string $beforeId = null, ?int $limit = null) :array;

    public function getModel(string $model) :array;

    public function createMessageBatch(array $messageBatch) :array;

    public function retrieveMessageBatch(string $messageBatchId) :array;

    public function retrieveMessageBatchResults(string $messageBatchId) :array;

    public function listMessageBatches(?string $afterId = null, ?string $beforeId = null, ?int $limit = null) :array;

    public function cancelMessageBatch(string $messageBatchId): array;

    public function deleteMessageBatch(string $messageBatchId) :array;

    public function createFile(array $file) : array;

    public function listFiles(?string $afterId = null, ?string $beforeId = null, ?int $limit = null, ?bool $useBeta = false) :array;

    public function getFileMetadata(string $fileId) :array;

    public function downloadFile(string $fileId) :string;

    public function deleteFile(string $fileId) :array;
}
