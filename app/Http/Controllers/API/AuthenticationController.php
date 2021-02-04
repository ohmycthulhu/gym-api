<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    protected $loginTypes;

    public function __construct()
    {
        $this->loginTypes = config('auth.providers');
    }

    /**
     * Route for trying to login
     *
     * @param Request $request
     * @param string $type
     *
     * @return JsonResponse
    */
    public function login(Request $request, string $type): JsonResponse {
        $params = $request->only(['email', 'password']);

        $guard = auth()->guard($type);

        $token = $guard->attempt($params);

        if ($token) {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $guard->user(),
            ]);
        } else {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }
    }

    /**
     * Get information about user
     *
     * @param string $type
     *
     * @return JsonResponse
    */
    public function me(string $type): JsonResponse {
        $user = auth()->guard($type)->user();
        if ($user) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
