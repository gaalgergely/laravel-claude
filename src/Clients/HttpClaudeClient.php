<?php

namespace GergelyGaal\LaravelClaude\Clients;

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;

class HttpClaudeClient implements ClaudeClientContract
{
    private $client;

    /**
     * @todo double check ...
     */
    private $config;

    public function __construct()
    {
        /**
         * @todo duplicated code fragment
         */
        $this->config = config('claude');

        $this->client = Http::baseUrl($this->config['base_url'])
            ->withHeaders([
                'x-api-key' => $this->config['api_key'],
                'anthropic-version' => '2023-06-01', // @todo move to config
            ])
            ->timeout($this->config['timeout'])
            ->retry($this->config['retries']);
    }

    /**
     * @note Messages
     * @todo implement image or file uploads
     * @todo change parameter to array (Message DTO)
     * @todo improve return value
     * @todo change the model dynamically
     */
    public function sendMessages(array $messages) :array
    {
        return ($this->client
            ->post('/messages', $messages)
            ->throw())->json();
    }

    /**
     * @todo implement image or file uploads
     * @todo change parameter to array (Message DTO)
     * @todo change the model dynamically
     */
    public function countMessageTokens(string $prompt)  :array
    {
        return ($this->client
            ->post('/messages/count_tokens', [
                'model' => $this->config['model'],
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])
            ->throw())->json();
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
    public function createMessageBatch() {}

    public function retrieveMessageBatch() {}

    public function listMessageBatches() {}

    public function cancelMessageBatch() {}

    public function deleteMessageBatch() {}

    /**
     * Files
     * @todo File DTO?
     * @todo fix downloadable to be TRUE
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
