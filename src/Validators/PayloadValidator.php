<?php

namespace GergelyGaal\LaravelClaude\Validators;

use GergelyGaal\LaravelClaude\Schemas\SchemaInterface;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use GergelyGaal\LaravelClaude\Exceptions\PayloadValidationException;
use Illuminate\Support\Arr;
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
        $validator = Validator::make($this->setDefaults($payload, $schema::defaults()), $schema::rules());

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

    private function setDefaults(array $payload, array $defaults)
    {
        if(isset($payload['requests']))
        {
            $requests = $payload['requests'];
            foreach($requests as $index => $request) {
                foreach($defaults as $key => $default) {

                    if(!isset($request['params'][$key]) || empty($request['params'][$key])) {
                        Arr::add($payload, "requests.$index.params.$key", $default);
                    }
                }
            }

            dd($payload);

            return $payload;

        } else {

            return array_merge($payload, $defaults);
        }
    }
}
