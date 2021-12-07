<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Psr\Http\Message\StreamInterface;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|StreamInterface
     * @throws ClientException|GuzzleException
     */
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
        } catch (ClientException $e) {
            if ($e->getCode() === 400) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request. Please enter a username or a password.',
                    'data'    => null
                ], $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your credentials are incorrect. Please try again.',
                    'data'    => null
                ], $e->getCode());
            }

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong on the server.',
                'data'    => null
            ], $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'regex:/^\([1-9]{2}\) (?:[2-8]|9[1-9])[0-9]{3}\-[0-9]{4}$/'],
            'password' => ['required', 'string', 'min:6']
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password
        ]);

        Client::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered.',
            'data'    => $user
        ]);
    }

    /**
     * Logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens->each(fn($token) => $token->delete());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'data'    => null
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function guest(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'You are not logged. Try to login in to the application.',
            'data'    => null
        ], 403);
    }
}
