<?php

namespace GaalGergely\LaravelClaude\Validators;

use GaalGergely\LaravelClaude\Schemas\SchemaInterface;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use GaalGergely\LaravelClaude\Exceptions\PayloadValidationException;
use Illuminate\Support\Arr;

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

        if ($validator->fails()) {

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

                        $payload = Arr::add($payload, "requests.$index.params.$key", $default);
                    }
                }
            }

        } else {

            $request = $payload;
            foreach($defaults as $key => $value) {

                if(!isset($request[$key]) || empty($request[$key])) {

                    $payload = Arr::add($payload, $key, $value);
                }
            }
        }
        return $payload;
    }
}

