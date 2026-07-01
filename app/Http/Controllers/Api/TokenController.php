<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    /**
     * Generate a new API token for the authenticated user.
     */
    public function generate(Request $request): JsonResponse
    {
        $token = Str::random(32);
        $request->user()->update(['api_token' => $token]);

        return response()->json([
            'token' => $token,
            'message' => 'Store this token securely. It will not be shown again.',
        ]);
    }

    /**
     * Revoke the user's API token.
     */
    public function revoke(Request $request): JsonResponse
    {
        $request->user()->update(['api_token' => null]);

        return response()->json(['ok' => true]);
    }
}
