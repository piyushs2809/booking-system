<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|string|email|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_type' => 'required|in:full_day,half_day,custom',
    
            // booking_slot required only for half_day, and nullable otherwise
            'booking_slot' => 'nullable|required_if:booking_type,half_day|in:first_half,second_half',
    
            // booking_from_time only validated for 'custom', nullable otherwise
            'booking_from_time' => 'nullable|required_if:booking_type,custom|date_format:H:i',
    
            // booking_to_time only validated for 'custom', nullable otherwise
            'booking_to_time' => 'nullable|required_if:booking_type,custom|date_format:H:i|after:booking_from_time',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->booking_type === 'custom') {
                $fromTime = $this->booking_from_time;
                $toTime = $this->booking_to_time;

                if ($fromTime && $toTime && $fromTime >= $toTime) {
                    $validator->errors()->add('booking_to_time', 'Booking "To Time" must be after "From Time".');
                }
            }
        });
    }

}
