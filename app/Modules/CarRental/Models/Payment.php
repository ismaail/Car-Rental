<?php

namespace App\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\PaymentMethod;
use App\Modules\CarRental\Enums\PaymentStatus;
use Database\Factories\Modules\CarRental\Models\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPayment
 */
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'rental_id',
        'deposit_id',
        'amount',
        'method',
        'status',
        'paid_at',
        'reference',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'method' => PaymentMethod::class,
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function deposit(): BelongsTo
    {
        return $this->belongsTo(Deposit::class);
    }
}
