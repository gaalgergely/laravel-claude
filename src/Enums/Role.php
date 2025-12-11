<?php
namespace GaalGergely\LaravelClaude\Enums;

enum Role: string
{
    case USER = 'user';
    case SYSTEM = 'system';
    case ASSISTANT = 'assistant';
}

