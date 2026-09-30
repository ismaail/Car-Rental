<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\Customer\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Customer
    {
        return DB::transaction(function () use ($data): Customer {
            $customer = Customer::query()->create(Arr::except($data, ['documents']));

            $this->storeDocuments($customer, $data['documents'] ?? []);

            return $customer->load('documents');
        });
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data): Customer {
            $customer->update(Arr::except($data, ['documents']));
            $this->storeDocuments($customer, $data['documents'] ?? []);

            return $customer->load('documents');
        });
    }

    /**
     * @param array<string, UploadedFile> $documents
     */
    private function storeDocuments(Customer $customer, array $documents): void
    {
        foreach ($documents as $type => $document) {
            $customer->documents()->create([
                'type' => $type,
                'file_path' => $document->store('car-rental/customers', 'public'),
            ]);
        }
    }
}
