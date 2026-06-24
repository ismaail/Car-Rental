<?php

declare(strict_types=1);

namespace App\Modules\Customer\Models;

use App\Modules\Customer\Types\CustomerDocumentType;
use Database\Factories\Modules\CarRental\Models\CustomerDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCustomerDocument
 */
#[UseFactory(CustomerDocumentFactory::class)]
class CustomerDocument extends Model
{
    /** @use HasFactory<CustomerDocumentFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'type',
        'file_path',
        'document_number',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => CustomerDocumentType::class,
            'expires_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
