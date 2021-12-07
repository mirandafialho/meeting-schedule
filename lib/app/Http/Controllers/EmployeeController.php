<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
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
                'message' => 'Found all employees.',
                'data'    => Employee::all()
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Employees not found.',
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
        try {
            $employee = new Employee;
            $employee->create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Found a employee.',
                'data'    => $employee
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
                'data'    => null
            ], 404);
        }
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
                'message' => 'Found a employee.',
                'data'    => Employee::findOrFail($id)
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
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
            $employee = Employee::findOrFail($id);
            $employee->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Employee updated.',
                'data'    => $employee
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
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
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted.',
                'data'    => null
            ], 204);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
                'data'    => null
            ], 404);
        }
    }
}
