<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Requests;

use App\Modules\CarRental\Enums\PaymentMethod;
use App\Modules\CarRental\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'rental_id' => ['nullable', 'exists:rentals,id'],
            'deposit_id' => ['nullable', 'exists:deposits,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'status' => ['required', Rule::enum(PaymentStatus::class)],
            'paid_at' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
