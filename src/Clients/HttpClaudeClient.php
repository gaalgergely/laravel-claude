<?php

namespace GergelyGaal\LaravelClaude\Clients;

use Illuminate\Support\Facades\Http;
use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;
use GergelyGaal\LaravelClaude\Payloads\Messages\MessagesPayloadValidator;
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
        // @todo test this part also !!!
        if (isset($messages['stream']) && $messages['stream'] === true) {

            $response = $this->client->withHeaders(['Accept' => 'text/event-stream'])->post('/messages', $messages);
            $resource = StreamWrapper::getResource($response->toPsrResponse()->getBody());
            $result = [];
            try {
                while (($line = fgets($resource)) !== false) {

                    $line = trim($line);

                    if ($line === '' || str_starts_with($line, ':')) {
                        continue; // ignore keep-alives
                    }
                    if ($line === 'data: [DONE]') {
                        break; // anthropic end marker
                    }

                    // other fields (e.g. event:) can be inspected if you need them
                    if (!str_starts_with($line, 'data: ')) {
                        continue;
                    }

                    $payload = json_decode(substr($line, 6), true, flags: JSON_THROW_ON_ERROR);
                    $result[] = $payload;
                }
                return $result;
            } finally {
                fclose($resource);
            }
        }

        $messages = (new MessagesPayloadValidator())->validate($messages);

        return ($this->client->post('/messages', $messages))->json();
    }

    public function countMessageTokens(array $messages)  :array
    {
        return ($this->client->post('/messages/count_tokens', $messages))->json();
    }

    /**
     * @note Models
     */
    public function listModels(?string $afterId = null, ?string $beforeId = null, ?int $limit = null) :array
    {
        $params = array_filter([
            'after_id'  => $afterId,
            'before_id' => $beforeId,
            'limit'     => $limit,
        ]);

        return ($this->client->get('/models', $params))->json();
    }

    public function getModel(string $model) :array
    {
        return ($this->client->get("/models/$model"))->json();
    }

    /**
     * @note Message Batches
     */
    public function createMessageBatch(array $messageBatch) :array
    {
        return ($this->client->post('/messages/batches', ['requests' => $messageBatch]))->json();
    }

    public function retrieveMessageBatch(string $messageBatchId) :array
    {
        return ($this->client->get("/messages/batches/$messageBatchId"))->json();
    }

    public function retrieveMessageBatchResults(string $messageBatchId) :array
    {
        $response = $this->client->get("/messages/batches/$messageBatchId/results");
        $stream = $response->toPsrResponse()->getBody();
        $resource  = StreamWrapper::getResource($stream);
        $result = [];
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
    public function listMessageBatches(?string $afterId = null, ?string $beforeId = null, ?int $limit = null) :array
    {
        $params = array_filter([
            'after_id'  => $afterId,
            'before_id' => $beforeId,
            'limit'     => $limit,
        ]);

        return ($this->client->get('/messages/batches', $params))->json();
    }

    public function cancelMessageBatch(string $messageBatchId): array
    {
        return ($this->client->post("/messages/batches/$messageBatchId/cancel"))->json();
    }

    public function deleteMessageBatch(string $messageBatchId) :array
    {
        return ($this->client->delete("/messages/batches/$messageBatchId"))->json();
    }

    /**
     * Files
     * @todo test how can i use with Messages(Batch) API
     * @note Files API in beta
     */
    public function createFile(array $file) : array
    {
        return ($this->client
            ->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])
            ->attach('file', $file['content'], $file['name'])
            ->post('/files'))
            ->json();
    }

    /**
     * @todo add pagination
     */
    public function listFiles(?string $afterId = null, ?string $beforeId = null, ?int $limit = null) :array
    {
        $params = array_filter([
            'after_id'  => $afterId,
            'before_id' => $beforeId,
            'limit'     => $limit,
        ]);

        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get('/files', $params))->json();
    }

    public function getFileMetadata(string $fileId) :array
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get("/files/$fileId"))->json();
    }

    /**
     * @todo TEST!
     * @see createFile -> downloadable to be TRUE ...
     */
    public function downloadFile(string $fileId) :string
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->get("/files/$fileId/content"))->stream();
    }

    public function deleteFile(string $fileId) :array
    {
        return ($this->client->withHeaders([
                'anthropic-beta' => 'files-api-2025-04-14'
            ])->delete("/files/$fileId"))->json();
    }
}
