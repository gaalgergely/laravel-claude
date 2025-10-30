<?php

namespace GergelyGaal\LaravelClaude\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array sendMessages(array $messages)
 * @method static array countMessageTokens(array $messages)
 * @method static array listModels(?string $afterId = null, ?string $beforeId = null, ?int $limit = null)
 * @method static array getModel(string $model)
 * @method static array createMessageBatch(array $messageBatch)
 * @method static array retrieveMessageBatch(string $messageBatchId)
 * @method static array retrieveMessageBatchResults(string $messageBatchId)
 * @method static array listMessageBatches(?string $afterId = null, ?string $beforeId = null, ?int $limit = null)
 * @method static array cancelMessageBatch(string $messageBatchId)
 * @method static array deleteMessageBatch(string $messageBatchId)
 * @method static array createFile(array $file)
 * @method static array listFiles(?string $afterId = null, ?string $beforeId = null, ?int $limit = null)
 * @method static array getFileMetadata(string $fileId)
 * @method static string downloadFile(string $fileId)
 * @method static array deleteFile(string $fileId)
 *
 * @see \GergelyGaal\LaravelClaude\Services\Claude
 */
class Claude extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GergelyGaal\LaravelClaude\Services\Claude::class;
    }
}
