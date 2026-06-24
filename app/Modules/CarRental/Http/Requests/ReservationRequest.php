<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Requests;

use App\Modules\Vehicle\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id', new Available()],
            'customer_id' => ['required', 'exists:customers,id'],
            'pickup_at' => ['required', 'date', 'after_or_equal:now'],
            'return_at' => ['required', 'date', 'after:pickup_at'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'return_location' => ['nullable', 'string', 'max:255'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'estimated_total' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
