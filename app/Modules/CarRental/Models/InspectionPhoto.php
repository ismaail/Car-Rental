<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Models;

use Database\Factories\Modules\CarRental\Models\InspectionPhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperInspectionPhoto
 */
class InspectionPhoto extends Model
{
    /** @use HasFactory<InspectionPhotoFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'inspection_id',
        'file_path',
        'caption',
    ];

    /**
     * @return BelongsTo<Inspection, $this>
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
