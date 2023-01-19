<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Http;

class PixPaymentAction
{
    public function execute(string $token, array $data)
    {
        $isSandbox = boolval(config('laravel-pagseguro.use-sandbox'));

        $url = config('laravel-pagseguro.host.production');

        if ($isSandbox) {
            $url = config('laravel-pagseguro.host.sandbox');
        }

        $payload =  [
            'reference_id' => $data['reference'],
            'customer' => [
                'name' => "Lanche Bem",
                'email' => 'lanchebemsempre@gmail.com',
                'tax_id' => "37282409000158",
            ],
            'items' => [
                [
                    'name' =>  "Cobrança- Lanche Bem",
                    'quantity' => 1,
                    'unit_amount' => intval(preg_replace("/[^0-9]/", "", str_replace(',', '.', $data['value'])))
                ]
            ],
            'qr_codes' => [
                [
                    'amount' => [
                        'value' => intval(preg_replace("/[^0-9]/", "", str_replace(',', '.', $data['value']))),
                    ],
                ]
            ]
        ];

        if (array_key_exists('customer', $data)) {
            $payload['customer'] = $data['customer'];
        }

        if (app()->environment('production')) {
            $payload['notification_urls'] = [config('app.url') . "/api/v1/stores/{$data['store']}/pagseguro/notification"];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $token,
            'x-api-version' => '4.0'
        ])->post(
            "{$url}/orders",
            $payload
        );

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        $checkout = $response->json();

        $result = [
            'id' => $checkout['id'],
            'ticket_url' => null,
            'status' => "PENDING",
            'created_at' => $checkout['created_at'],
            'payment_response' => $checkout['qr_codes'],
            'payed' => false,
        ];

        return $result;
    }
}
