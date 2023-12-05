<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SanctumController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::query()->where('email', $request->get('email'))->first();

        if (! $user || ! Hash::check($request->get('password'), $user->getAttribute('password'))) {
            throw ValidationException::withMessages([
                'email' => ['密码认证错误'],
            ]);
        }

        return $user->createToken($request->get('device_name'))->plainTextToken;
    }
}
