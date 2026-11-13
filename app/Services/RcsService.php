<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RcsService
{
    public static function send(
        array $numbers,
        string $template,
        array $variables = [],
        string $callback = null
    ) {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . config('services.rcs.token'),
                'Content-Type'  => 'application/json',
            ])->post(config('services.rcs.url'), [
                'BotId'        => config('services.rcs.bot_id'),
                'TemplateName' => $template,
                'Destination'  => $numbers,
                'Var'          => (object) $variables, // must be object
                'CallbackData' => $callback ?? '',
            ]);

            if ($response->failed()) {
                Log::error('RCS API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'RCS API responded with error (' . $response->status() . '): ' . ($response->json('message') ?? $response->body()),
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => true,
                'message' => 'RCS message sent successfully!',
                'data' => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('RCS Service Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to connect to RCS service: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
