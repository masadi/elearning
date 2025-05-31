<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $namaField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'Email' : 'Username';
        request()->merge([$field => $login]);
        $request->validate(
            [
                $field => 'required|string|exists:users,'.$field ,
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ],
            [
                $field.'.required' => $namaField.' tidak boleh kosong',
                $field.'.exists' => $namaField.' tidak terdaftar',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );
        $credentials = request([$field,'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'user' => NULL,
                'message' => [
                    'password' => 'Password salah!',
                ]
            ],422);
        }

        $pengguna = $request->user();
        $tokenResult = $pengguna->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;
        $collection = [];
        foreach($pengguna->allPermissions(['description as action', 'name as subject']) as $perm){
            $collection[] = [
                'action' => $perm->action,
                'subject' => $perm->subject,
            ];
        }
        $data = [
            'accessToken' =>$token,
            'userData' => $pengguna,
            'token_type' => 'Bearer',
            'permission' => [
                [
                    'action' => 'manage',
                    'subject' => 'all',
                ]
            ],
            'userAbilityRules' => $collection,
        ];
        return response()->json($data);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
