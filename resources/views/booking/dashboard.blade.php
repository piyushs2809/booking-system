@extends('layouts.app')

@section('title', 'Dashboard - Booking System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    <a href="{{ route('booking.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Booking
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Your Bookings</h5>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->customer_name }}</strong>
                            </td>
                            <td>{{ $booking->customer_email }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $booking->booking_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                @if($booking->booking_type === 'full_day')
                                    <span class="badge booking-type-full">Full Day</span>
                                @elseif($booking->booking_type === 'half_day')
                                    <span class="badge booking-type-half">Half Day</span>
                                @else
                                    <span class="badge booking-type-custom">Custom</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->booking_type === 'half_day')
                                    <small class="text-muted">
                                        {{ ucfirst(str_replace('_', ' ', $booking->booking_slot)) }}
                                    </small>
                                @elseif($booking->booking_type === 'custom')
                                    <small class="text-muted">
                                        {{ date('g:i A', strtotime($booking->booking_from_time)) }} - 
                                        {{ date('g:i A', strtotime($booking->booking_to_time)) }}
                                    </small>
                                @else
                                    <small class="text-muted">9:00 AM - 6:00 PM</small>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('booking.edit', $booking) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" 
                                          action="{{ route('booking.destroy', $booking) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No bookings found</h5>
                <p class="text-muted">You haven't created any bookings yet.</p>
                <a href="{{ route('booking.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Booking
                </a>
            </div>
        @endif
    </div>
</div>
@endsection