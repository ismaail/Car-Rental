<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Requests;

use App\Modules\Customer\Types\CustomerDocumentType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'national_id_number' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'driving_license_number' => ['nullable', 'string', 'max:255'],
            'driving_license_expires_at' => ['nullable', 'date'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function documentLabels(): array
    {
        return collect(CustomerDocumentType::cases())
            ->mapWithKeys(fn (CustomerDocumentType $type) => [$type->value => str($type->value)->replace('_', ' ')->title()->value()])
            ->all()
        ;
    }
}
