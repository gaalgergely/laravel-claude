<?php

namespace GergelyGaal\LaravelClaude\Payloads\Messages;

final readonly class MessageFragment
{
    public function __construct(
        public string $type,
        public string $text, //@todo fix for images
    ) {}

    public function toArray(): array
    {
        return ['type' => $this->type, 'text' => $this->text];
    }
}
