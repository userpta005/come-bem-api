<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Http;

class SplitPaymentAction
{
    public function execute(array $data)
    {
        $isSandbox = boolval(config('laravel-pagseguro.use-sandbox'));

        $url = config('laravel-pagseguro.host.production');

        if ($isSandbox) {
            $url = config('laravel-pagseguro.host.sandbox');
        }

        $value = intval(preg_replace("/[^0-9]/", "", str_replace(',', '.', $data['value'])));

        $payload =  [
            'reference_id' => $data['reference'],
            'description' => 'Recarga de crédito - Come Bem',
            'amount' => [
                [
                    'value' => $value,
                    'currency' => 'BRL'
                ]
            ],
            'payment_method' => [
                'type' => 'CREDIT_CARD',
                'installments' => $data['card']['installments'],
                'capture' => false,
                'soft_descriptor' => 'Come Bem',
                'card' => [
                    'number' => $data['card']['number'],
                    'exp_month' => $data['card']['exp_month'],
                    'exp_year' => $data['card']['exp_year'],
                    'security_code' => $data['card']['security_code'],
                    'holder' => [
                        'name' => $data['card']['holder'],
                    ]
                ]

            ],
            'splits' => [
                'method' => 'FIXED',
                'receivers' => [
                    [
                        'account' => [
                            'id' => config('laravel-pagseguro.account_id')
                        ],
                        'amount' => [
                            'value' => intval($value * 3.99 / 100),
                        ]
                    ],
                    [
                        'account' => [
                            'id' => $data['account']
                        ],
                        'amount' => [
                            'value' => $value - intval($value * 3.99 / 100),
                        ]
                    ]
                ]
            ]
        ];


        if (app()->environment('production')) {
            $payload['notification_urls'] = [config('app.url') . "/api/v1/pagseguro/notification"];
        }

        dd($url);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . config('laravel-pagseguro.token'),
            'x-api-version' => '4.0'
        ])->post(
            "{$url}/charges",
            []
        );

        if ($response->failed()) {
            throw new Exception($response->body());
        }

        $checkout = $response->json();

        $result = [
            'id' => $checkout['id'],
            'ticket_url' => null,
            'status' => $checkout['status'],
            'created_at' => $checkout['created_at'],
            'payment_response' => $checkout['payment_response'],
            'payed' => false,
        ];

        return $result;
    }
}
