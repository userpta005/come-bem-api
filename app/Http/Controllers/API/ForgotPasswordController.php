<?php

namespace App\Http\Controllers\API;

use App\Models\Settings;
use App\Models\User;
use App\Notifications\ResetPasswordPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação.', $validator->errors()->toArray(), 422);
        }

        $settings = Settings::where('store_id', $request->get('store')['id'])->first();

        if(!$settings){
            return $this->sendError('Configurações da app não adicionada.', [], 403);
        }

        if(!$settings->reset_password_url){
            return $this->sendError('Url de reset de senha não configurada.', [], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('Email não cadastrado.');
        }

        $token = app('auth.password.broker')->createToken($user);

        DB::table(config('auth.passwords.users.table'))->insert([
            'email' => $user->email,
            'token' => $token
        ]);

        $url = $settings->reset_password_url;

        $user->notify(
            new ResetPasswordPortal("$url?token=$token")
        );

        return $this->sendResponse(
            [],
            'O link de redefinição de senha foi enviado no seu email.'
        );
    }
}
