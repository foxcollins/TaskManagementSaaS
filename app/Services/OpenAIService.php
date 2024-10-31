<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function generateSuggestion($prompt, $maxTokens)
    {
        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',//'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => $maxTokens,
            ],
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        return $responseBody['choices'][0]['message']['content'];
    }
}

