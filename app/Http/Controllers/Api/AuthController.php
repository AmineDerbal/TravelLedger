<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {


        $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8',
             'password_confirmation' => 'required|same:password',

         ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user');

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        // login with name or email and with password

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or username
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (!Auth::attempt([$fieldType => $login, 'password' => $password])) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'message' => __('auth.failed'),
                ],
            ], 422);
        }
        $user = Auth::user();

        $role = $user->getRoleNames()->first();
        $permissions = $user->getPermissionForCASL();

        $userdata = [

            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,

        ];

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json($userdata)->cookie(
            'access_token',
            $token,
            60 * 24 * 30 * 60,
            '/',
            null,
            true,
            true,
        )->cookie(
            'userAbilityRules',
            json_encode($permissions),
            60 * 24 * 30,
            '/',
            null,
            false,
            false
        );


    }

    public function logout(Request $request)
    {

        $accessToken = $request->cookie('access_token');

        if ($accessToken) {
            $tokenId = explode('|', $accessToken)[0]; // Token format is usually id|token
            $token = PersonalAccessToken::find($tokenId);

            if ($token) {
                $token->delete();
            }
        }

        return response()->json([
            'message' => 'Logout successful',
        ], 200)->withCookie(Cookie::forget('access_token'))->withCookie(Cookie::forget('userAbilityRules'));
    }
}
