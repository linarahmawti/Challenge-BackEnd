<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $validated['user_id'] = Auth::id();

        $booking = Booking::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking->load('car')
        ], 201);
    }

    public function index()
    {
        $bookings = Booking::with('car')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
}
