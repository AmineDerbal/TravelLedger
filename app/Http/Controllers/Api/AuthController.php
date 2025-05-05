<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request){
 
   
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
 
         return response()->json([
             'message' => 'Registration successful',
             'user' => $user,
         ], 201);
     }
   
     public function login(Request $request){
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

        $userdata = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json(['user' => $user])->cookie(
            'access_token',
            $token,
            60 * 24 * 30 * 60,
            '/',
            null,
            true,
            true,
        )->cookie(
            'user_data',
            json_encode($userdata),
            60 * 24 * 30 * 60,
            '/',
            null,
            true,
            true,
        );
        

    }

    public function logout(Request $request){
    
        $request->user()->currentAccessToken()->delete();
     
        return response()->json([
            'message' => 'Logout successful',
        ], 200)->withCookie(Cookie::forget('access_token'))->withCookie(Cookie::forget('user_data'));
    }
}