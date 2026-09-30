<?php

declare(strict_types=1);

namespace App\Modules\Reservation\Requests;

use App\Models\User;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Vehicle\Rules\Available;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Reservation $reservation */
        $reservation = $this->route('reservation');

        /** @var User $user */
        $user = $this->user();

        return $user->can('confirm', $reservation);
    }

    /**
     * @return array<string, list<string|ValidationRule>>
     */
    public function rules(): array
    {
        /** @var Reservation $reservation */
        $reservation = $this->route('reservation');

        return [
            'vehicle_id' => [new Available($reservation)],
        ];
    }

    protected function prepareForValidation(): void
    {
        /** @var Reservation $reservation */
        $reservation = $this->route('reservation');

        $this->merge([
            'vehicle_id' => $reservation->vehicle_id,
            'pickup_at' => $reservation->pickup_at,
            'return_at' => $reservation->return_at,
        ]);
    }
}
