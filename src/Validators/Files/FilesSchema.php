<?php

namespace GergelyGaal\LaravelClaude\Validators\Files;

final class FilesSchema
{
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
