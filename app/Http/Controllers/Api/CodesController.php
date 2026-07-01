<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class CodesController extends Controller
{
    /**
     * Return all TOTP codes for a user authenticated by API token.
     * GET /api/codes
     * Header: Authorization: Bearer <token>
     */
    public function index(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Missing authorization token'], 401);
        }

        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $google2fa = new Google2FA();
        $accounts = $user->twoFactorAccounts()
            ->with('category')
            ->get()
            ->map(fn($account) => [
                'id' => $account->id,
                'label' => $account->label,
                'issuer' => $account->issuer,
                'category' => $account->category?->name,
                'code' => $google2fa->getCurrentOtp($account->secret),
                'remaining' => 30 - (time() % 30),
            ]);

        return response()->json([
            'accounts' => $accounts,
            'count' => $accounts->count(),
        ]);
    }
}
