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
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->withOptions([
                'json' => [
                    'model' => $this->config['model'],
                ]
            ])
            ->timeout($this->config['timeout'])
            ->retry($this->config['retries'])
            /**
             * @todo remove temporary fix! or config? -> log if disabled?
             */
            ->withOptions([
                'verify' => false
            ]);
    }

    /**
     * @note Messages
     * @todo implement image or file uploads
     * @todo change parameter to array (Message DTO)
     * @todo improve return value
     */
    public function sendMessages(string $prompt) :array
    {

        return ($this->client
            ->post('/messages', [
                'max_tokens' => 800, // @todo move to config
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])
            ->throw())->json();
    }

    /**
     * @todo implement image or file uploads
     * @todo change parameter to array (Message DTO)
     */
    public function countMessageTokens(string $prompt)  :array
    {
        return ($this->client
            ->post('/messages/count_tokens', [
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ])
            ->throw())->json();
    }

    /**
     * @note Models
     * @todo add query parameters
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
     */
    public function createFile() {}

    public function listFiles() {}

    public function getFileMetadata() {}

    public function downloadFile() {}

    public function deleteFile() {}
}
