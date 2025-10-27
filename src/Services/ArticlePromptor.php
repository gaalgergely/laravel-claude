<?php

namespace GergelyGaal\LaravelClaude\Services;

use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;

class ArticlePromptor
{
    public function __construct(private ClaudeClientContract $client) {}

    public function generateArticle(string $topic): string
    {
        $prompt = "Write a Medium-style article about {$topic}. Tone: clear, practical.";
        return $this->client->generate($prompt);
    }
}
