<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Models\Rental;
use Mpdf\Mpdf;

class ContractPdfService
{
    public function output(Rental $rental): string
    {
        $rental->loadMissing(['vehicle', 'customer', 'reservation', 'deposit', 'payments']);

        $pdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 12,
            'margin_right' => 12,
            'margin_bottom' => 16,
            'margin_left' => 12,
        ]);

        $pdf->WriteHTML(view('car-rental.contracts.rental', [
            'rental' => $rental,
        ])->render());

        return $pdf->OutputBinaryData();
    }
}
