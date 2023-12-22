<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SanctumController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if ($email === null || $password === null) {
            return response()->json([
                'message' => __('cat/auth.email_or_password_required'),
            ], 401);
        }

        $user = User::query()->where('email', $request->get('email'))->first();

        if (! $user || ! Hash::check($request->get('password'), $user->getAttribute('password'))) {
            return response()->json([
                'message' => __('cat/auth.password_does_not_match'),
            ], 401);
        }

        $token = $user->createToken($request->get('device_name'))->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }
}
