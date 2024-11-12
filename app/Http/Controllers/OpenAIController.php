<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroqService; // Asegúrate de importar tu servicio
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OpenAIController extends BaseController
{
    protected $openAIService;

    public function __construct(GroqService $openAIService)
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
            $maxTokens = $request->type === 'title' ? 150 : 500; // ajustar según el tipo
            $prompt = "Dame solo una lista de títulos de tareas, sin numeración, sin introducción ni explicaciones, donde cada título contenga la palabra: ' . $request->prompt . '. Muestra cada título en una línea separada.";
            $suggestion = $this->openAIService->generateSuggestion($prompt, $maxTokens);
            Log::info(['OpenAIController '=> $suggestion]);
            return $this->sendResponse($suggestion, 'Suggestion created successfully.', 200);
        } catch (\Exception $e) {
            Log::error('OpenAI API error', ['exception' => $e]);
            return response()->json(['error' => 'Error al comunicarse con OpenAI'], 500);
        }
    }

    public function suggestDescription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|max:500',
            'type' => 'required|in:title,description',
            'title'=>'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            // Usar el servicio de OpenAI
            $maxTokens = $request->type === 'title' ? 150 : 500; // ajustar según el tipo
            $prompt = "Dame solo una lista de descripciones de tareas, sin numeración, sin introducción ni explicaciones, donde cada descripción contenga la palabra: ' . $request->prompt .' y tenga relacion con el titulo:'.$request->title.'  Muestra cada desfripción en una línea separada.";
            $suggestion = $this->openAIService->generateSuggestion($prompt, $maxTokens);
            Log::info(['OpenAIController ' => $suggestion]);
            return $this->sendResponse($suggestion, 'Suggestion created successfully.', 200);
        } catch (\Exception $e) {
            Log::error('OpenAI API error', ['exception' => $e]);
            return response()->json(['error' => 'Error al comunicarse con OpenAI'], 500);
        }
    }
}
