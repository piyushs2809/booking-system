@extends('layouts.app')

@section('title', 'Create Booking - Booking System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Create New Booking</h4>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" 
                                   name="customer_name" 
                                   value="{{ old('customer_name') }}">
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">Customer Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" 
                                   name="customer_email" 
                                   value="{{ old('customer_email') }}">
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_date" class="form-label">Booking Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('booking_date') is-invalid @enderror" 
                                   id="booking_date" 
                                   name="booking_date" 
                                   value="{{ old('booking_date') }}" 
                                   min="{{ date('Y-m-d') }}">
                            @error('booking_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="booking_type" class="form-label">Booking Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('booking_type') is-invalid @enderror" 
                                    id="booking_type" 
                                    name="booking_type">
                                <option value="">Select Booking Type</option>
                                <option value="full_day" {{ old('booking_type') === 'full_day' ? 'selected' : '' }}>Full Day</option>
                                <option value="half_day" {{ old('booking_type') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                                <option value="custom" {{ old('booking_type') === 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('booking_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Half Day Slot Selection --}}
                    <div class="mb-3" id="slotSelection" style="display: none;">
                        <label for="booking_slot" class="form-label">Booking Slot <span class="text-danger">*</span></label>
                        <select class="form-select @error('booking_slot') is-invalid @enderror" 
                                id="booking_slot" 
                                name="booking_slot">
                            <option value="">Select Slot</option>
                            <option value="first_half" {{ old('booking_slot') === 'first_half' ? 'selected' : '' }}>First Half (9:00 AM - 1:00 PM)</option>
                            <option value="second_half" {{ old('booking_slot') === 'second_half' ? 'selected' : '' }}>Second Half (1:00 PM - 6:00 PM)</option>
                        </select>
                        @error('booking_slot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Custom Time Selection --}}
                    <div id="customTimeSelection" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="booking_from_time" class="form-label">From Time <span class="text-danger">*</span></label>
                                <input type="time" 
                                       class="form-control @error('booking_from_time') is-invalid @enderror" 
                                       id="booking_from_time" 
                                       name="booking_from_time" 
                                       value="{{ old('booking_from_time') }}">
                                @error('booking_from_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="booking_to_time" class="form-label">To Time <span class="text-danger">*</span></label>
                                <input type="time" 
                                       class="form-control @error('booking_to_time') is-invalid @enderror" 
                                       id="booking_to_time" 
                                       name="booking_to_time" 
                                       value="{{ old('booking_to_time') }}">
                                @error('booking_to_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingType = document.getElementById('booking_type');
    const slotSelection = document.getElementById('slotSelection');
    const customTimeSelection = document.getElementById('customTimeSelection');
    const bookingSlot = document.getElementById('booking_slot');
    const fromTime = document.getElementById('booking_from_time');
    const toTime = document.getElementById('booking_to_time');

    function toggleFields() {
        const type = bookingType.value;
        
        // Hide all conditional fields
        slotSelection.style.display = 'none';
        customTimeSelection.style.display = 'none';
        
        // Clear required attributes
        bookingSlot.removeAttribute('required');
        fromTime.removeAttribute('required');
        toTime.removeAttribute('required');
        
        // Show relevant fields based on type
        if (type === 'half_day') {
            slotSelection.style.display = 'block';
            bookingSlot.setAttribute('required', 'required');
        } else if (type === 'custom') {
            customTimeSelection.style.display = 'block';
            fromTime.setAttribute('required', 'required');
            toTime.setAttribute('required', 'required');
        }
    }

    // Initialize on page load
    toggleFields();
    
    // Listen for changes
    bookingType.addEventListener('change', toggleFields);
});
</script>
@endsection
