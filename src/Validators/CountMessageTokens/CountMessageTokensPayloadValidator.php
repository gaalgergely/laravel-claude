<?php

namespace GergelyGaal\LaravelClaude\Validators\CountMessageTokens;

use Illuminate\Support\Facades\Validator;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;

use Illuminate\Support\Facades\Log;

final class CountMessageTokensPayloadValidator
{
    public function __construct(private array $schemaRules = [])
    {
        $this->schemaRules = $schemaRules ?: CountMessageTokensSchema::rules();
    }

    /**
     * @param  array  $payload
     */
    public function validate(array $payload): array
    {
        //$arrayPayload = $payload instanceof MessagesData ? $payload->toArray() : $payload;
        $arrayPayload = $payload;

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
