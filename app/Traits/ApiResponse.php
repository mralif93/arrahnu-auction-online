<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success response.
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an error response.
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return an authentication success response with user and token.
     */
    protected function authResponse($user, string $token, string $message = 'Authentication successful'): JsonResponse
    {
        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'is_admin' => $user->is_admin,
                'status' => $user->status,
            ],
            'token' => $token,
            'token_type' => 'Bearer'
        ], $message);
    }

    /**
     * Return a 2FA required response with session token.
     */
    protected function twoFactorResponse($user, string $sessionToken, int $expiresIn): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'A verification code has been sent to your email.',
            'requires_2fa' => true,
            'data' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'session_token' => $sessionToken,
                'expires_in' => $expiresIn
            ]
        ]);
    }
} 