<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Enums\CustomerDocumentType;
use App\Modules\CarRental\Http\Requests\CustomerRequest;
use App\Modules\CarRental\Models\Customer;
use App\Modules\CarRental\Services\CustomerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customerService)
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(): View
    {
        return view('car-rental.customers.index', [
            'customers' => Customer::query()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('car-rental.customers.form', [
            'customer' => new Customer(),
            'documentLabels' => $this->documentLabels(),
        ]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = $this->customerService->create($request->validated());

        return redirect()->route('car-rental.customers.show', $customer)
            ->with('status', 'Customer created successfully.')
        ;
    }

    public function show(Customer $customer): View
    {
        return view('car-rental.customers.show', [
            'customer' => $customer->load(['documents', 'reservations.vehicle', 'rentals.vehicle']),
        ]);
    }

    public function edit(Customer $customer): View
    {
        return view('car-rental.customers.form', [
            'customer' => $customer,
            'documentLabels' => $this->documentLabels(),
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->customerService->update($customer, $request->validated());

        return redirect()->route('car-rental.customers.show', $customer)
            ->with('status', 'Customer updated successfully.')
        ;
    }

    /**
     * @return array<string, string>
     */
    private function documentLabels(): array
    {
        return collect(CustomerDocumentType::cases())
            ->mapWithKeys(fn (CustomerDocumentType $type) => [$type->value => str($type->value)->replace('_', ' ')->title()->value()])
            ->all()
        ;
    }
}
