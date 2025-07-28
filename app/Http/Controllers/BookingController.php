<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * List all bookings
     */
    public function index()
    {
        try {
            $bookings = Booking::with(['user', 'service'])->get();

            return response()->json([
                'status'  => 'success',
                'message' => 'Bookings fetched successfully',
                'data'    => $bookings,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to fetch bookings',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show a single booking by ID
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['user', 'service'])->find($id);

            if ($booking) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Booking found',
                    'data'    => $booking,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Booking not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to fetch booking',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'      => 'required|exists:users,id',
            'service_id'   => 'required|exists:services,id',
            'booking_date' => 'required|date|after:today',
            'status'       => 'nullable|in:booked,pending,canceled,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $service = Service::find($request->input('service_id'));
        if ($service->status !== 'available') {
            return response()->json([
                'status'  => 'fail',
                'message' => 'This service is not available for booking',
            ], 400);
        }

        try {
            $booking = Booking::create(array_merge(
                $validator->validated(),
                ['status' => $request->input('status', 'pending')]
            ));

            return response()->json([
                'status'  => 'success',
                'message' => 'Booking created successfully',
                'data'    => $booking,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to create booking',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update booking by ID
     */
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Booking not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'user_id'      => 'sometimes|required|exists:users,id',
                'service_id'   => 'sometimes|required|exists:services,id',
                'booking_date' => 'sometimes|nullable|date',
                'status'       => 'sometimes|required|in:booked,pending,canceled,completed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $booking->update($validator->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'Booking updated successfully',
                'data'    => $booking,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to update booking',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete booking by ID
     */
    public function destroy($id)
    {
        try {
            $booking = Booking::find($id);

            if (!$booking) {
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Booking not found',
                ], 404);
            }

            $booking->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Booking deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Failed to delete booking',
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }
}
