<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SessionController extends BaseController
{
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->toArray(), 422);
        }

        $inputs = $request->all();

        if (Auth::attempt(array('email' => $inputs['email'], 'password' => $inputs['password']))) {

            /**
             * @var \App\Models\User
             */
            $user = Auth::user();
            $user->loadMissing([
                'people.client',
                'people.dependent.accounts.store.people'
            ]);

            if ($user->status->isInactive()) {
                return $this->sendError('Usuário não ativado. Entre em contato com o suporte !', [], 403);
            }

            $token = $user->createToken(config('app.key'));

            return $this->sendResponse(
                [
                    'user' => $user,
                    'token' => $token->plainTextToken
                ],
                "Login successfully."
            );
        }

        return $this->sendError('Email ou senha inválidos', [], 401);
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(
            ['message' => 'Successfully logged out'],
            201
        );
    }
}
