<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show booking list with user and services
     */
    public function bookingList()
    {
        try {
            $booking = Booking::with(['user','service'])->get();

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
}
