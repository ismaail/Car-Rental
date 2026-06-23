<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\InspectionType;
use Database\Factories\Modules\CarRental\Models\InspectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperInspection
 */
class Inspection extends Model
{
    /** @use HasFactory<InspectionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'rental_id',
        'type',
        'inspected_at',
        'mileage',
        'fuel_level',
        'notes',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => InspectionType::class,
            'inspected_at' => 'datetime',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionPhoto::class);
    }
}
