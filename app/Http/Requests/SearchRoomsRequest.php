<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRoomsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public search functionality
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $today = now()->startOfDay();

        return [
            'check_in_date' => [
                'required',
                'date',
                'after_or_equal:' . $today->format('Y-m-d'),
            ],
            'check_out_date' => [
                'required',
                'date',
                'after:check_in_date',
            ],
            'guests' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
            'amenities' => [
                'sometimes',
                'array',
            ],
            'amenities.*' => [
                'integer',
                'exists:amenities,id',
            ],
            'price_min' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],
            'price_max' => [
                'sometimes',
                'nullable',
                'numeric',
                'gt:price_min',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'check_in_date' => 'check-in date',
            'check_out_date' => 'check-out date',
            'amenities' => 'amenities',
            'price_min' => 'minimum price',
            'price_max' => 'maximum price',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'check_in_date.after_or_equal' => 'The check-in date must be today or later.',
            'check_out_date.after' => 'The check-out date must be after the check-in date.',
        ];
    }

    /**
     * Calculate the number of nights between check-in and check-out dates.
     */
    public function getNightsCount(): int
    {
        $checkIn = Carbon::parse($this->check_in_date);
        $checkOut = Carbon::parse($this->check_out_date);

        return $checkIn->diffInDays($checkOut);
    }
}
