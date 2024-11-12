<?php

namespace App\Services;

use LucianoTonet\GroqLaravel\Facades\Groq;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GroqService{

    public function generateSuggestion($prompt, $maxTokens)
    {
        $response = Groq::chat()->completions()->create([
            'model' => 'llama-3.1-70b-versatile',  // Check available models at console.groq.com/docs/models
            'max_tokens' => $maxTokens,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);
        Log::info(['GroqServise - Response'=>$response]);
        return $response['choices'][0]['message']['content']; // "Hey there! I'm doing great! How can I help you today?"
    }
}