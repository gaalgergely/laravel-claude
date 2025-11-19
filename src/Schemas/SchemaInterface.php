<?php

namespace GergelyGaal\LaravelClaude\Schemas;

interface SchemaInterface
{
    public static function rules(): array;
    public static function defaults(): array;
}
