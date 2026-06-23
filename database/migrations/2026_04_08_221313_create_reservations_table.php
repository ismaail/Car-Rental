<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('pending')->index();
            $table->dateTime('pickup_at');
            $table->dateTime('return_at');
            $table->string('pickup_location')->nullable();
            $table->string('return_location')->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('estimated_total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['vehicle_id', 'pickup_at', 'return_at']);
            $table->index(['customer_id', 'pickup_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
