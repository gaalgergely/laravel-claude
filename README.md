# Laravel Claude

Laravel Claude is a Laravel 10/11/12 compatible package for strongly typed, convenient access to the Anthropic Claude API. It builds on the Laravel HTTP client, validates every payload before sending, and ships with a Facade for quick use.

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12
- A valid Anthropic Claude API key (`CLAUDE_API_KEY`)

## Installation

```bash
composer require gaalgergely/laravel-claude
```

If you want to customize the defaults, publish the configuration:

```bash
php artisan vendor:publish --tag=claude
```

## Configuration

Default values live in `config/claude.php`:

```php
return [
    'base_url' => env('CLAUDE_BASE_URL', 'https://api.anthropic.com/v1'),
    'api_key' => env('CLAUDE_API_KEY'),
    'anthropic_version' => env('CLAUDE_ANTHROPIC_VERSION', '2023-06-01'),
    'model' => env('CLAUDE_MODEL', 'claude-sonnet-4-5-20250929'),
    'max_tokens' => env('CLAUDE_MAX_TOKENS', 1024),
    'temperature' => env('CLAUDE_TEMPERATURE', 1.0),
    'timeout' => env('CLAUDE_TIMEOUT', 60),
    'retries' => env('CLAUDE_RETRIES', 1),
    'anthropic-beta' => env('CLAUDE_ANTROPHIC_BETA', 'files-api-2025-04-14'),
];
```

Key environment variables:

- `CLAUDE_API_KEY` (required)
- `CLAUDE_MODEL`, `CLAUDE_MAX_TOKENS`, `CLAUDE_TEMPERATURE` – defaults that validation fills in when missing fields are allowed.
- `CLAUDE_TIMEOUT`, `CLAUDE_RETRIES` – HTTP client tuning.
- `CLAUDE_ANTROPHIC_BETA` – beta header required for the Files API.

## Usage

Access the service through the `Claude` facade or via dependency injection.

### Quick message example

```php
use GaalGergely\LaravelClaude\Facades\Claude;

$response = Claude::sendMessages([
    'system' => 'You are a helpful assistant.',
    'messages' => [
        ['role' => 'user', 'content' => 'Tell me a joke!'],
    ],
]);

// Plain text reply
$text = data_get($response, 'content.0.text');
```

### Streaming responses

```php
$events = Claude::sendMessages([
    'messages' => [
        ['role' => 'user', 'content' => 'Give me a short overview of Laravel!'],
    ],
    'stream' => true,
]);

foreach ($events as $event) {
    // Individual SSE payloads arrive as JSON arrays
}
```

### Counting tokens

```php
$result = Claude::countMessageTokens([
    'messages' => [
        ['role' => 'user', 'content' => 'How many tokens is this?'],
    ],
]);

$tokenCount = $result['input_tokens'] ?? null;
```

### Querying models

```php
$models = Claude::listModels(limit: 20);
$opus = Claude::getModel('claude-3-opus-20240229');
```

### Message batches

```php
$batch = Claude::createMessageBatch([
    'requests' => [
        [
            'custom_id' => 'req-1',
            'params' => [
                'messages' => [
                    ['role' => 'user', 'content' => 'Share a motivational quote!'],
                ],
            ],
        ],
    ],
]);

$status = Claude::retrieveMessageBatch($batch['id']);
$results = Claude::retrieveMessageBatchResults($batch['id']);
```

### File operations

```php
$file = Claude::createFile([
    'name' => 'notes.txt',
    'content' => "Project notes...",
]);

$files = Claude::listFiles();
$metadata = Claude::getFileMetadata($file['id']);
$content = Claude::downloadFile($file['id']);
Claude::deleteFile($file['id']);
```

## Validation and error handling

Every call validates the outgoing payload. On failure a `PayloadValidationException` is thrown with all validation messages and the original data:

```php
use GaalGergely\LaravelClaude\Exceptions\PayloadValidationException;

try {
    Claude::sendMessages([...]);
} catch (PayloadValidationException $e) {
    logger()->error('Claude payload error', $e->toArray());
}
```

If the API key is missing an `ApiKeyIsMissingException` is thrown, so ensure `CLAUDE_API_KEY` is set.
