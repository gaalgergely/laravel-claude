<?php

namespace GergelyGaal\LaravelClaude\Validators;

use GergelyGaal\LaravelClaude\Schemas\SchemaInterface;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;

use Illuminate\Support\Facades\Log;

final class PayloadValidator
{
    private string $schema;

    public function __construct(string $schemaClass)
    {
        if (!is_subclass_of($schemaClass, SchemaInterface::class)) {
            throw new InvalidArgumentException('Schema must implement '.SchemaInterface::class);
        }

        $this->schema = $schemaClass;
    }

    /**
     * @param  array  $payload
     */
    public function validate(array $payload): array
    {
        $schema = $this->schema;
        $validator = Validator::make(array_merge($payload, $schema::defaults()), $schema::rules());

        // @todo make the message informative

        if ($validator->fails()) {

            // @todo just for testing
            Log::error('Claude payload validation failed', [
                'errors' => $validator->errors()->toArray(),
                'payload' => $payload,
            ]);

            throw PayloadValidationException::fromValidator($validator);
        }

        return $validator->validated();
    }
}
