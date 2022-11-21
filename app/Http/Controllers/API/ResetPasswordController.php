<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'token' => 'required|string',
            "password" => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de Validação.', $validator->errors()->toArray(), 422);
        }

        $credentials = $request->all();

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return $this->sendError("Token Inválido.", [], 403);
        }

        if ($reset_password_status == Password::INVALID_USER) {
            return $this->sendError("Email Inválido.", [], 403);
        }


        return $this->sendResponse(
            [],
            'A senha foi alterada com sucesso.'
        );
    }
}
