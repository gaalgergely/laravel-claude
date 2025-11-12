<?php

namespace GergelyGaal\LaravelClaude\Exceptions;

use Illuminate\Support\MessageBag;
use RuntimeException;

final class PayloadValidationException extends RuntimeException
{
    public function __construct(
        private array $errors,
        private array $payload
    ) {
        parent::__construct('Claude payload validation failed.');
    }

    public static function fromValidator($validator): self
    {
        return new self($validator->errors()->toArray(), $validator->getData());
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors(),
            'payload' => $this->payload(),
        ];
    }

    public function toMessageBag(): MessageBag
    {
        return new MessageBag($this->errors);
    }
}
