<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Found all clients.',
                'data'    => Client::all()
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Clients not found.',
                'data'    => null
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not allowed',
            'data'    => null
        ], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Founded a client.',
                'data'    => Client::findOrFail($id)
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'data'    => null
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  integer $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $client = Client::findOrFail($id);
            $client->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Client updated.',
                'data'    => $client
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'data'    => null
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json([
                'success' => true,
                'message' => 'Client deleted.',
                'data'    => null
            ], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found.',
                'data'    => null
            ], 404);
        }
    }
}
