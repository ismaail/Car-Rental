<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->dateTime('inspected_at');
            $table->unsignedInteger('mileage')->default(0);
            $table->string('fuel_level')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['rental_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
