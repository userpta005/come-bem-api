<?php

return [
    /* DEFINE SE SERÁ UTILIZADO O AMBIENTE DE TESTES */
    'use-sandbox' =>  env('PAGSEGURO_SANDBOX', true),



    /*
     * Informe abaixo o nome / url das rotas de aplicação para notificações
     * e redirecionamento após pagamento
     * Parâmetro: "route-name" para nome de rota laravel ou "fixed" para url fixa (URL completa)
     * Ex. 01: "route-name" => "tela-de-obrigado" (Nome de Rota)
     * Ex. 02: "fixed" => "http://minhaloja.com.br/pagamento/tela-de-obrigado" (URL Fixa)
     *
     * PARA MAIS INFORMAÇÕES VIDE:
     * https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html
     */
    'routes' => [
        'redirect' => [
            'fixed' => env('APP_URL'), // Criar uma rota com este nome
        ],
        'notification' => [
            'callback' => ['App\Actions\NotificationAction', 'execute'], // Callable callback to Notification function (notificationInfo) : void {}
            'credential' => 'default', // Callable resolve credential function (notificationCode) : Credentials {}
            'route-name' => 'pagseguro.notification', // Criar uma rota com este nome
        ],
    ],





    /*
     * ATENÇÃO: Não altere as configurações abaixo
     * */
    'host' => [
        'production' => 'https://api.pagseguro.com',
        'sandbox' => 'https://sandbox.api.pagseguro.com'
    ],

    'notificacation' => [
        'production' => 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications',
        'sandbox' => 'https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications'
    ],
];
