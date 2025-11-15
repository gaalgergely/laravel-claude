<?php

namespace GergelyGaal\LaravelClaude\Validators\Messages;

use Illuminate\Support\Facades\Validator;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;

use GergelyGaal\LaravelClaude\Payloads\Messages\MessagesData;

use Illuminate\Support\Facades\Log;

final class MessagesPayloadValidator
{
    public function __construct(private array $schemaRules = [])
    {
        $this->schemaRules = $schemaRules ?: MessagesSchema::rules();
    }

    /**
     * @param  array|ClaudeMessageData  $payload
     */
    public function validate(array|MessagesData $payload): array
    {
        $arrayPayload = $payload instanceof MessagesData ? $payload->toArray() : $payload;

        $validator = Validator::make($arrayPayload, $this->schemaRules);

        // @todo make the message informative

        if ($validator->fails()) {

            // @todo just for testing
            Log::error('Claude payload validation failed', [
                'errors' => $validator->errors()->toArray(),
                'payload' => $arrayPayload,
            ]);

            throw PayloadValidationException::fromValidator($validator);
        }

        return $validator->validated();
    }
}
