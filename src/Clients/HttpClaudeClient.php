<?php

namespace GergelyGaal\LaravelClaude\Clients;

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use Illuminate\Support\Facades\Storage;

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
                'content-type' => 'application/json',
                /**
                 * @todo Files API is in beta
                 */
                'anthropic-beta' => 'files-api-2025-04-14'
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
    public function createFile() : array
    {
        /**
         * @todo fix this ...
         */
        /*$stream = Storage::readStream('test.pdf');
        if (! $stream) {
            throw new \RuntimeException('Failed to open file.');
        }*/

        /**
         *  @todo fix downloadable to be TRUE
        */

        return (Http::attach(
                'file',
                Storage::readStream('test.pdf'),
                'test.pdf'
            )
            ->withHeaders([
                'x-api-key' => $this->config['api_key'],
                'anthropic-version' => '2023-06-01', // @todo move to config
                //'content-type' => 'application/json',
                /**
                 * @todo Files API is in beta
                 */
                'anthropic-beta' => 'files-api-2025-04-14'
            ])
            /**
             * @todo remove temporary fix! or config? -> log if disabled?
             */
            ->withOptions([
                'verify' => false,
                /*'json' => [
                    'downloadable' => true
                ]*/
            ])
            //->asMultipart()
            ->post($this->config['base_url'] . '/files', [
                'downloadable' => 'true',
                'purpose' => 'user_uploaded'
            ])->throw())->json();

        /*return ($this->client
            /**
             * @todo refactor
             */
            //->attach('file', Storage::readStream('private/test.pdf'), 'test.pdf')
            /*->attach('file', Storage::get('private/test.pdf'), 'test.pdf')
            ->post('/files')
            ->throw())->json();*/
    }

    public function listFiles() :array
    {
        return ($this->client->get('/files')->throw())->json();
    }

    public function getFileMetadata(string $fileId) :array
    {
        return ($this->client->get("/files/$fileId")->throw())->json();
    }

    public function downloadFile(string $fileId) :string
    {
        return ($this->client->get("/files/$fileId/content")->throw())->stream();
    }

    public function deleteFile(string $fileId) :array
    {
        return ($this->client->delete("/files/$fileId")->throw())->json();
    }
}
