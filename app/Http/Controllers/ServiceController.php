<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * List all services
     */
    public function index()
    {
        try {
            $services = Service::all();
            return response()->json([
                'status'  => 'success',
                'message' => 'Services fetched successfully',
                'data'    => $services,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to fetch services',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show single service by ID
     */
    public function show($id)
    {
        try {
            $service = Service::find($id);
            if ($service) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Service found',
                    'data'    => $service,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Service not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to fetch service',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store new service
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255|unique:services,name',
            'description' => 'required|string',
            'status'      => 'required|in:available,not_available',
            'price'       => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $service = Service::create($validator->validated());
            return response()->json([
                'status'  => 'success',
                'message' => 'Service created successfully',
                'data'    => $service,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to create service',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update existing service by ID
     */
    public function update(Request $request, $id)
    {
        try {
            $service = Service::find($id);
            if (!$service) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Service not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name'        => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'status'      => 'sometimes|required|in:available,not_available',
                'price'       => 'sometimes|required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $service->update($validator->validated());
            return response()->json([
                'status'  => 'success',
                'message' => 'Service updated successfully',
                'data'    => $service,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to update service',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete service by ID
     */
    public function destroy($id)
    {
        try {
            $service = Service::find($id);
            if (!$service) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Service not found',
                ], 404);
            }

            $service->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Service deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to delete service',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }
}
