<?php

namespace App\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\CustomerDocumentType;
use Database\Factories\Modules\CarRental\Models\CustomerDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCustomerDocument
 */
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
