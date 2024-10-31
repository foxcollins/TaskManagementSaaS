<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService; // Asegúrate de importar tu servicio
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OpenAIController extends BaseController
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function suggest(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|max:500',
            'type' => 'required|in:title,description'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            // Usar el servicio de OpenAI
            $maxTokens = $request->type === 'title' ? 5 : 10; // ajustar según el tipo
            $suggestion = $this->openAIService->generateSuggestion($request->prompt, $maxTokens);
            return $this->sendResponse($suggestion, 'Suggestion created successfully.', 200);
        } catch (\Exception $e) {
            Log::error('OpenAI API error', ['exception' => $e]);
            return response()->json(['error' => 'Error al comunicarse con OpenAI'], 500);
        }
    }
}
