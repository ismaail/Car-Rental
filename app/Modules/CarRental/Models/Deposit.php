<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\DepositStatus;
use Database\Factories\Modules\CarRental\Models\DepositFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDeposit
 */
class Deposit extends Model
{
    /** @use HasFactory<DepositFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'rental_id',
        'amount',
        'status',
        'collected_at',
        'released_at',
        'withheld_amount',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'withheld_amount' => 'decimal:2',
            'status' => DepositStatus::class,
            'collected_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Rental, $this>
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}
