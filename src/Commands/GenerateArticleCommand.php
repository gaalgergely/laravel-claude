<?php

namespace GergelyGaal\LaravelClaude\Commands;

use Illuminate\Console\Command;
use GergelyGaal\LaravelClaude\Services\ArticlePromptor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GenerateArticleCommand extends Command
{
    protected $signature = 'claude:generate-article {--topic=}';
    protected $description = 'Generate a Medium draft via Claude AI';

    public function handle(ArticlePromptor $promptor)
    {
        $topic = $this->option('topic') ?? $this->ask('Topic?');
        $article = $promptor->generateArticle($topic);
        $path = 'claude/' . Str::slug($topic) . '.md';
        Storage::disk('local')->put($path, $article);
        $this->info("Draft saved to storage/app/{$path}");
    }
}
