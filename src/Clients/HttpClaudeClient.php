<?php

namespace GergelyGaal\LaravelClaude\Clients;

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use GuzzleHttp\Psr7\StreamWrapper;

class HttpClaudeClient implements ClaudeClientContract
{
    private $client;

    public function __construct()
    {
        $config = config('claude');

        $this->client = Http::baseUrl($config['base_url'])
            ->withHeaders([
                'x-api-key' => $config['api_key'],
                'anthropic-version' => $config['anthropic_version'],
            ])
            ->timeout($config['timeout'])
            ->retry($config['retries']);
    }

    /**
     * @note Messages
     */
    public function sendMessages(array $messages) :array
    {
        return ($this->client->post('/messages', $messages)->throw())->json();
    }

    public function countMessageTokens(array $messages)  :array
    {
        return ($this->client->post('/messages/count_tokens', $messages)->throw())->json();
    }

    /**
     * @note Models
     * @todo add pagination
     * @see https://docs.claude.com/en/api/models-list (before_id, after_id, limit etc.)
     */
    public function listModels() :array
    {
        return ($this->client->get('/models')->throw())->json();
    }

    public function getModel(string $model) :array
    {
        return ($this->client->get("/models/$model")->throw())->json();
    }

    /**
     * Message Batches
     */
    public function createMessageBatch(array $messageBatch) :array
    {
        return ($this->client->post('/messages/batches', ['requests' => $messageBatch])->throw())->json();
    }

    public function retrieveMessageBatch(string $messageBatchId) :array
    {
        return ($this->client->get("/messages/batches/$messageBatchId")->throw())->json();
    }

    public function retrieveMessageBatchResults(string $messageBatchId) :array
    {
        $response = ($this->client->get("/messages/batches/$messageBatchId/results")->throw());
        $result = [];
        $stream = $response->toPsrResponse()->getBody();
        $resource  = StreamWrapper::getResource($stream);
        try {
            while (($line = fgets($resource)) !== false) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                $record = json_decode($line, true, flags: JSON_THROW_ON_ERROR);
                $result[] = $record;
            }
        } finally {

            fclose($resource);
        }
        return $result;
    }

    /**
     * @todo add pagination
     */
    public function listMessageBatches() :array
    {
        return ($this->client->get('/messages/batches')->throw())->json();
    }

    public function cancelMessageBatch() {}

    public function deleteMessageBatch() {}

    /**
     * Files
     * @todo test how can i use with Messages(Batch) API
     */
    public function createFile(array $file) : array
    {
        return ($this->client
            ->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])
            ->attach('file', $file['content'], $file['name'])
            ->post('/files')
            ->throw())->json();
    }

    /**
     * @todo add pagination
     */
    public function listFiles() :array
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get('/files')->throw())->json();
    }

    public function getFileMetadata(string $fileId) :array
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get("/files/$fileId")->throw())->json();
    }

    /**
     * @todo TEST!
     * @see createFile -> downloadable to be TRUE ...
     */
    public function downloadFile(string $fileId) :string
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get("/files/$fileId/content")->throw())->stream();
    }

    public function deleteFile(string $fileId) :array
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->delete("/files/$fileId")->throw())->json();
    }
}
