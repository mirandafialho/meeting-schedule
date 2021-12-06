<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(config('services.passport.endpoint'), [
                'form_params' => [
                    'grant_type'    => 'password',
                    'client_id'     => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username'      => $request->username,
                    'password'      => $request->password
                ]
            ]);

            return $response->getBody();
        } catch (BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:255',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password
        ]);

        Client::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'user_id' => $user->id
        ]);

        return $user;
    }

    /**
     * Logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        return response()->json([
            'Logged out successfully.'
        ]);
    }
}
