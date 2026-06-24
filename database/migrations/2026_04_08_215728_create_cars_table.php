<?php

declare(strict_types=1);

use App\Modules\Vehicles\Models\Brand;
use App\Modules\Vehicles\Models\Modele;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('car_registration_number')->unique();
            $table->foreignIdFor(Brand::class)->index()->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Modele::class)->index()->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
