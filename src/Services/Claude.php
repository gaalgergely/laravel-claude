<?php

namespace GergelyGaal\LaravelClaude\Services;

use GergelyGaal\LaravelClaude\Contracts\ClaudeClientContract;

class Claude
{
    public function __construct(private ClaudeClientContract $client) {}

    public function sendMessages(string $topic): array
    {
        //$prompt = "Write a Medium-style article about {$topic}. Tone: clear, practical.";
        //return $this->client->generate($prompt);

        return $this->client->sendMessages($topic);
    }

    public function countMessageTokens(string $topic): array
    {

        return $this->client->countMessageTokens($topic);
    }

    public function listModels(): array
    {

        return $this->client->listModels();
    }

    public function getModel(string $model): array
    {

        return $this->client->getModel($model);
    }
}
