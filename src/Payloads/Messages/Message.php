<?php

namespace GergelyGaal\LaravelClaude\Payloads\Messages;

use GergelyGaal\LaravelClaude\Enums\Role;

final readonly class Message
{
    public function __construct(
        public Role $role,
        /** @var array<int, MessageFragment> */
        public array $content,
    ) {}

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => array_map(fn ($fragment) => $fragment->toArray(), $this->content),
        ];
    }
}
