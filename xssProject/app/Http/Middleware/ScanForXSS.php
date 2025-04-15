<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\AttackLog;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ScanForXSS
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('ScanForXSS middleware called');
        Log::info("Request: ", $request->all());
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $userInput = $request->all();

            Log::info("User Input: ", $userInput);

            $response = $this->analyzeInputWithAI($userInput);

            if ($response['status'] === 'malicious') {
                $this->logAttack($userInput);
                return response()->json([
                    'error' => 'Malicious input detected. Please check your input.'
                ], 400);
            }
        }

        return $next($request);
    }

    protected function analyzeInputWithAI(array $inputData)
    {
        $inputText = json_encode($inputData, JSON_PRETTY_PRINT);
        Log::info("Sending Data to OpenAI");

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a web application firewall. 
                    Scan the following JSON data for potential XSS (Cross Site Scripting) payloads.
                    Look for script tags, event handlers (e.g., onload, onerror), or JavaScript links. 
                    If any are found, classify as "malicious", otherwise "safe".',
                ],
                [
                    'role' => 'user',
                    'content' => "Input:\n\n$inputText",
                ],
            ],
            'temperature' => 0,
            'max_tokens' => 10,
        ]);

        $data = $response->json();
        Log::info('OpenAI Response: ', $data);

        if ($response->ok() && isset($data['choices'][0]['message']['content'])) {
            $classification = strtoupper(trim($data['choices'][0]['message']['content']));

            if (str_contains($classification, 'MALICIOUS')) {
                return ['status' => 'malicious'];
            }
        }

        return ['status' => 'safe'];
    }

    protected function logAttack(array $inputData)
    {
        
        $attackLog = new AttackLog();

        $attackLog->user_id = $inputData['user_id'];
        $attackLog->content = json_encode($inputData['comment']);
        $attackLog->occured_at = Carbon::now();

        $attackLog->save();
    }
}
