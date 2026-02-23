<?php

namespace App\Http\Services;

class TxImageDetectService
{
    /**
     * Detect transaction details from an image (receipt, invoice, proof of transfer)
     *
     * @param string $imagePath Local path to the image
     * @return array|null Returns ['type' => 'in'|'out', 'description' => string, 'amount' => float] or null
     */
    public function detect($imagePath): ?array
    {
        if (!file_exists($imagePath)) {
            \Illuminate\Support\Facades\Log::error("TxImageDetectService: Image not found at {$imagePath}");
            return null;
        }

        // Get API key from config or .env (e.g. GEMINI_API_KEY)
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            \Illuminate\Support\Facades\Log::error("TxImageDetectService: GEMINI_API_KEY is not set.");
            return null;
        }

        try {
            // Read image and encode to base64
            $mimeType = mime_content_type($imagePath);
            $base64Image = base64_encode(file_get_contents($imagePath));

            // The prompt instructing the AI
            $prompt = "You are an expert at extracting transaction data from images of receipts, invoices, or transfer proofs (mostly in Indonesian).\n" .
                "Analyze the image and extract the following information:\n" .
                "- type: 'in' if it is money received/incoming, 'out' if it is money spent/outgoing.\n" .
                "- amount: the total amount or grand total as a pure number (no currency symbols or separators, just float, e.g., 50000).\n" .
                "- description: a very short description of the transaction (e.g., 'Makan siang', 'Transfer dari Budi', 'Belanja bulanan') max 50 characters.\n" .
                "- category: a short label for the transaction category, max 2 words (e.g., 'makanan', 'transfer', 'belanja bulanan').\n" .
                "Return ONLY a valid JSON object without any markdown formatting. The JSON keys MUST be exactly: type, amount, description, category.";

            // Call Google Gemini API
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $apiKey,
            ])->timeout(90)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data'      => $base64Image
                                ]
                            ],
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1, // Low temperature for factual extraction
                    'response_mime_type' => 'application/json', // Force valid JSON response
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Get the raw text output from Gemini
                $textResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

                // Parse it into an array
                $parsed = json_decode(trim($textResult), true);

                // Ensure the parsed JSON contains our expected keys
                if (json_last_error() === JSON_ERROR_NONE && isset($parsed['type'], $parsed['amount'], $parsed['description'])) {
                    return [
                        'type' => in_array($parsed['type'], ['in', 'out']) ? $parsed['type'] : 'out',
                        'amount' => (float) $parsed['amount'],
                        'description' => ucfirst($parsed['description']),
                        'category' => strtolower($parsed['category'] ?? explode(' ', $parsed['description'])[0]),
                    ];
                } else {
                    \Illuminate\Support\Facades\Log::warning("TxImageDetectService: Failed to parse Gemini output or missing keys.", [
                        'raw_output' => $textResult
                    ]);
                }
            } else {
                \Illuminate\Support\Facades\Log::error("TxImageDetectService API Error: " . $response->body());
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("TxImageDetectService Exception: " . $e->getMessage());
        }

        return null; // Return null if detection failed
    }
}
