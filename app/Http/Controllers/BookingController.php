<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\AuthService;


class BookingController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function dashboard()
    {
        $user = $this->authService->user();
        
        // Optimized query with pagination for large datasets
        $bookings = Booking::where('user_id', $user->id)
                          ->orderBy('booking_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('booking.dashboard', compact('bookings'));
    }

    public function create()
    {
        return view('booking.create');
    }

    public function store(BookingRequest $request)
    {
        $user = $this->authService->user();
        $data = $request->validated();

        // Critical overlap check with optimized query
        $hasOverlap = Booking::hasOverlap(
            $data['booking_date'],
            $data['booking_type'],
            $data['booking_slot'] ?? null,
            $data['booking_from_time'] ?? null,
            $data['booking_to_time'] ?? null
        );

        if ($hasOverlap) {
            return back()->withErrors(['booking_date' => 'This booking conflicts with an existing booking.'])
                        ->withInput();
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'booking_date' => $data['booking_date'],
            'booking_type' => $data['booking_type'],
            'booking_slot' => $data['booking_slot'] ?? null,
            'booking_from_time' => $data['booking_from_time'] ?? null,
            'booking_to_time' => $data['booking_to_time'] ?? null,
        ]);

        return redirect()->route('dashboard')
                        ->with('success', 'Booking created successfully!');
    }

    public function edit(Booking $booking)
    {
        $user = $this->authService->user();
        
        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        return view('booking.edit', compact('booking'));
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $user = $this->authService->user();
        
        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validated();

        // Check overlap excluding current booking
        $hasOverlap = Booking::hasOverlap(
            $data['booking_date'],
            $data['booking_type'],
            $data['booking_slot'] ?? null,
            $data['booking_from_time'] ?? null,
            $data['booking_to_time'] ?? null,
            $booking->id
        );

        if ($hasOverlap) {
            return back()->withErrors(['booking_date' => 'This booking conflicts with an existing booking.'])
                        ->withInput();
        }

        $booking->update([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'booking_date' => $data['booking_date'],
            'booking_type' => $data['booking_type'],
            'booking_slot' => $data['booking_slot'] ?? null,
            'booking_from_time' => $data['booking_from_time'] ?? null,
            'booking_to_time' => $data['booking_to_time'] ?? null,
        ]);

        return redirect()->route('dashboard')
                        ->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $user = $this->authService->user();
        
        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->delete();

        return redirect()->route('dashboard')
                        ->with('success', 'Booking deleted successfully!');
    }
}
