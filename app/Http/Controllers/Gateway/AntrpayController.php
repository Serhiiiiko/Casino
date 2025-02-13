<?php

namespace App\Http\Controllers\Api\Gateways;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\Gateway;

class AntrpayController extends Controller
{
    /**
     * Создание платежной сессии через Antrpay.
     */
    public function getSessionRequest(Request $request)
    {
        $user = auth('api')->user();
        $amountUsd = $request->input('amount'); // Сумма в USD (внутренняя валюта)

        // Получаем параметры Antrpay
        $antrpayUri = env('ANTRPAY_URI'); // URL API Antrpay
        $publicKey  = env('ANTRPAY_PUBLIC_KEY');
        $secretKey  = env('ANTRPAY_SECRET_KEY');
        $rate       = env('ANTRPAY_RUB_PER_USD', 100);

        // Конвертируем сумму из USD в RUB
        $amountRub = $amountUsd * $rate;
        $amountRubFormatted = number_format($amountRub, 2, '.', '');

        // Генерируем уникальный reference_id для транзакции
        $reference = (string) Str::uuid();

        // Формируем тело запроса к Antrpay
        $payload = [
            "data" => [
                "type" => "payment-invoice",
                "attributes" => [
                    "service"          => "payment_card_rub_hpp", // Используйте нужный тип сервиса из документации
                    "commerce_account" => "default",
                    "currency"         => "RUB",
                    "amount"           => $amountRubFormatted,
                    "flow"             => "charge",
                    "customer"         => [
                        "reference_id" => (string)$user->id,
                    ],
                    "return_url" => url('/payment/return'),
                    "callback_url" => url('/webhooks/antrpay'),
                    "test_mode"    => true,
                    "reference_id" => $reference,
                ]
            ]
        ];

        try {
            $client = new Client();
            $response = $client->post($antrpayUri, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    // Пример авторизации: передаем публичный ключ и HMAC-подпись
                    'X-Auth-Public' => $publicKey,
                    'X-Signature'   => hash_hmac('sha256', json_encode($payload), $secretKey),
                ],
                'body' => json_encode($payload)
            ]);
            $result = json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Antrpay session error: " . $e->getMessage());
            return response()->json(['status' => false, 'error' => $e->getMessage()], 400);
        }

        // Из ответа получаем URL для редиректа пользователя
        $redirectUrl = $result['data']['attributes']['payment_page_url'] ?? null;
        if ($redirectUrl) {
            return response()->json([
                'status' => true,
                'redirect_url' => $redirectUrl,
                'reference_id' => $reference
            ]);
        } else {
            return response()->json(['status' => false, 'error' => 'Payment URL not found'], 400);
        }
    }

    /**
     * Обработка вебхуков от Antrpay.
     */
    public function webhooks(Request $request)
    {
        $payload = $request->getContent();
        $signatureHeader = $request->header('X-Signature');
        $secretKey = env('ANTRPAY_SECRET_KEY');

        // Пример проверки подписи (уточните алгоритм согласно документации Antrpay)
        $computedSignature = base64_encode(sha1($secretKey . $payload . $secretKey, true));
        if (!$signatureHeader || $computedSignature !== $signatureHeader) {
            Log::warning('Antrpay webhook signature mismatch', ['computed' => $computedSignature, 'header' => $signatureHeader]);
            return response('Invalid signature', 400);
        }

        $data = json_decode($payload, true);
        $attributes = $data['data']['attributes'] ?? [];
        $reference = $attributes['reference_id'] ?? null;
        $status = $attributes['status'] ?? null;

        // Здесь необходимо найти транзакцию по reference_id и обновить её статус.
        // Например:
        // $transaction = PaymentTransaction::where('reference_id', $reference)->first();
        // if($transaction) { ... }

        Log::info('Antrpay webhook received', ['reference' => $reference, 'status' => $status, 'raw' => $attributes]);

        return response('OK', 200);
    }

    /**
     * Возвращает публичный ключ Antrpay для фронтенда.
     */
    public function getPublicKey()
    {
        $gateway = Gateway::first();
        if ($gateway && $gateway->antrpay_is_enable) {
            return response()->json([
                'antrpay_public_key' => $gateway->antrpay_public_key,
                'antrpay_uri' => $gateway->antrpay_uri,
            ]);
        }
        return response()->json(['error' => 'Antrpay not configured'], 400);
    }
}
