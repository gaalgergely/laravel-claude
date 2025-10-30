<?php

namespace GergelyGaal\LaravelClaude\Contracts;

use GergelyGaal\LaravelClaude\DTOs\MessagesPayload;

interface ClaudeClientContract
{
    public function sendMessages(array $messages): array;

    public function countMessageTokens(array $messages): array;

    public function listModels() :array;

    public function getModel(string $model) :array;

    public function createMessageBatch(array $messageBatch) :array;

    public function retrieveMessageBatch(string $messageBatchId) :array;

    public function retrieveMessageBatchResults(string $messageBatchId) :array;

    public function listMessageBatches() :array;

    public function cancelMessageBatch(string $messageBatchId): array;

    public function createFile(array $file) : array;

    public function listFiles() :array;

    public function getFileMetadata(string $fileId) :array;

    public function downloadFile(string $fileId) :string;

    public function deleteFile(string $fileId) :array;
}
