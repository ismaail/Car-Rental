<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Requests;

use App\Modules\CarRental\Enums\InspectionItemStatus;
use App\Modules\CarRental\Enums\InspectionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InspectionRequest extends FormRequest
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
            'type' => ['required', Rule::enum(InspectionType::class)],
            'inspected_at' => ['required', 'date'],
            'mileage' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.label' => ['required_with:items', 'string', 'max:255'],
            'items.*.status' => ['required_with:items', Rule::enum(InspectionItemStatus::class)],
            'items.*.notes' => ['nullable', 'string'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'max:5120'],
        ];
    }
}
