<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show booking list according user
     */
    public function bookings(Request $request)
    {
        try {
            $booking = Booking::with(['service'])->where('user_id',$request->auth['id'])->get();

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
