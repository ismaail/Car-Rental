<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use Database\Factories\Modules\CarRental\Models\VehicleImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperVehicleImage
 */
class VehicleImage extends Model
{
    /** @use HasFactory<VehicleImageFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'vehicle_id',
        'path',
        'caption',
        'is_primary',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
