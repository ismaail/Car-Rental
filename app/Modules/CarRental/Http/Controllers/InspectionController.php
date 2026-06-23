<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Http\Requests\InspectionRequest;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Services\InspectionService;
use Illuminate\Http\RedirectResponse;

class InspectionController extends Controller
{
    public function __construct(private readonly InspectionService $inspectionService) {}

    public function store(InspectionRequest $request, Rental $rental): RedirectResponse
    {
        $this->authorize('update', $rental);
        $this->inspectionService->create($rental, $request->validated());

        return back()->with('status', 'Inspection saved successfully.');
    }
}
