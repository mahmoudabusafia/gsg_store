<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
            'device_name' => ['required'],
            'abilities' => ['nullable'],
        ]);

        $user = User::where('email', $request->username)->orWhere('mobile', $request->username)->first();

        if( !$user || !Hash::check($request->password, $user->password)){
            return Response::json([
                'message' => 'Invalid username and password combinations',
            ], 401);
        }

        $abilities = $request->input('abilities', ['*']);

        if($abilities && is_string($abilities)){
            $abilities = explode(',', $abilities);
        }

        $token = $user->createToken($request->device_name, $abilities);

        // $accessToken = $user->tokens()->latest()->first();
        // $accessToken = PersonalAccessToken::findToken($token->plainTextToken);
        // $accessToken->forceFill([
        //     'ip' => $request->ip(),
        // ])->save();
        
        // $user->tokens()->latest()->first()->update([
        //     'ip' => $request->ip(),
        // ]);

        Log::info("User $user->name Logged in from " . $request->ip(), [
            'ip' => $request->ip(),
            'device' => $request->input('device_name')
        ]);

        return Response::json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }


    public function destroy()
    {
        $user = Auth::guard('sanctum')->user();

        // Revoke (delete) all user tokens
        // $user->tokens()->delete();
        // $user->tokens($id)->delete(); لو انا مررت اي دي التوكن
        
        // Revoke (delete) current user token
        Log::info("User $user->name Logged out ");

        $user->currentAccesstoken()->delete();
    }
}
