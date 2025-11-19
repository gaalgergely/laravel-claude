<?php
namespace GergelyGaal\LaravelClaude\Schemas;

abstract class SchemaAbstract
{
    protected static array $config;

    public static function boot(): void
    {
        if (!isset(static::$config)) {
            static::$config = config('claude');
        }
    }

    public static function defaults()
    {
        static::boot();
    }
}
