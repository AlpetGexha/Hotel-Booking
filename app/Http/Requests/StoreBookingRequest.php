<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreBookingRequest extends FormRequest
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
        $rules = [
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1', 'max:10'],
            'booking_for' => ['required', Rule::in(['self', 'other'])],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ];

        if ($this->booking_for !== 'other') {
            return $rules;
        }

        // Add guest details validation rules when booking for others
        return array_merge($rules, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'room_type_id' => 'room type',
            'check_in_date' => 'check-in date',
            'check_out_date' => 'check-out date',
            'booking_for' => 'booking recipient',
            'special_requests' => 'special requests',
            'name' => 'guest name',
            'email' => 'guest email',
            'phone' => 'guest phone number',
        ];
    }
}
