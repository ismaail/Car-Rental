<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use App\Modules\CarRental\Enums\InspectionItemStatus;
use Database\Factories\Modules\CarRental\Models\InspectionItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperInspectionItem
 */
class InspectionItem extends Model
{
    /** @use HasFactory<InspectionItemFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'inspection_id',
        'label',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => InspectionItemStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Inspection, $this>
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
