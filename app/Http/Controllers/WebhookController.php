<?php

namespace App\Http\Controllers;

use App\Facades\Piwapi;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Services\TxDetectionService;
use App\Http\Services\TxImageDetectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Helper;

class WebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, TxDetectionService $txDetectionService, TxImageDetectService $txImageDetectService)
    {
        $secret = config('services.piwapi.webhook_secret', env('WEBHOOK_SECRET'));

        // Check webhook secret
        if ($request->has('secret') && $request->input('secret') === $secret) {
            $payloadType = $request->input('type');
            $payloadData = $request->input('data');

            Log::info("Webhook Type: {$payloadType}", ['data' => $payloadData]);

            // Specifically handle WhatsApp messages
            if ($payloadType === 'whatsapp' && is_array($payloadData)) {
                $phone = $payloadData['phone'] ?? null;
                $message = $payloadData['message'] ?? '';
                $attachmentUrl = $payloadData['attachment'] ?? null;
                $timestamp = $payloadData['timestamp'] ?? time();

                $phone = Helper::normalizePhone($phone);

                if (!$phone) {
                    Piwapi::sendText($phone, "*NOMOR ANDA TIDAK TERDAFTAR*");

                    return response()->json(['status' => 'error', 'message' => 'Phone missing'], 400);
                }

                // 1. Find user by phone
                $user = User::where('phone', $phone)->first();
                if (!$user) {
                    Piwapi::sendText($phone, "*NOMOR ANDA BELUM TERDAFTAR*");
                    return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
                }

                // 2. Detect text message if any
                $detectedData = null;
                if (!empty(trim($message))) {
                    $detectedData = $txDetectionService->detect($message);
                }

                $publicImagePath = null;

                // 3. Handle attachment download & vision AI
                if (!empty($attachmentUrl)) {
                    try {
                        // Ensure unique secure filename
                        $extension = pathinfo(parse_url($attachmentUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        $filename = $timestamp . '-' . uniqid() . '-' . $user->id . '.' . $extension;

                        // Download the file stream and save to public storage
                        $imageContents = Http::get($attachmentUrl)->body();
                        Storage::disk('public')->put('tx_image/' . $filename, $imageContents);

                        $publicImagePath = 'tx_image/' . $filename;
                        $absoluteFilePath = storage_path('app/public/tx_image/' . $filename);

                        // 4. Detect image content
                        $imageDetection = $txImageDetectService->detect($absoluteFilePath);

                        // If the AI analyzed the image, we override/merge with the text analysis
                        if ($imageDetection) {
                            // Convert false/null array elements safely
                            $detectedData = array_merge((array) $detectedData, $imageDetection);
                        }
                    } catch (\Exception $e) {
                        Log::error("Webhook Attachment Error: " . $e->getMessage());
                    }
                }

                // Process final detected data or fallback
                $type = $detectedData['type'] ?? 'out';
                $amount = $detectedData['amount'] ?? 0;
                $description = $detectedData['description'] ?? rtrim(mb_strimwidth($message, 0, 100, "..."));

                if (empty($description)) {
                    $description = "Transaksi Baru";
                }

                // 5. Save to database as pending
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'phone' => $phone,
                    'amount' => $amount,
                    'description' => $description,
                    'type' => $type,
                    'image' => $publicImagePath,
                    'status' => 'pending',
                ]);

                Piwapi::sendText($user->phone, "* TRANSAKSI TERDETEKSI *
* TIPE :* {$type}
* JUMLAH :* {$amount}
* DESKRIPSI :* {$description}
* STATUS :* pending
Silahkan konfirmasi atau tolak transaksi dengan balas 'ya' atau 'tolak' di chat ini.");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction saved as pending',
                    'transaction_id' => $transaction->id
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook received successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid secret'
        ], 403);
    }
}
