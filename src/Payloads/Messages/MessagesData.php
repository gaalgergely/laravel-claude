<?php

namespace GergelyGaal\LaravelClaude\Payloads\Messages;

final readonly class MessagesData
{
    public function __construct(
        public string $model,
        /** @var array<int, Message> */
        public array $messages,
        public ?int $maxTokens = null, // @todo config
        public float $temperature = 1.0, // @todo config check docs
        // @todo stream
        // @todo check docs for other fields
    ) {}

    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'messages' => array_map(fn ($message) => $message->toArray(), $this->messages),
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature,
        ];
    }
}
