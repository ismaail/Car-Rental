<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('vin')->nullable()->unique();
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('color')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('weekly_rate', 10, 2)->nullable();
            $table->decimal('monthly_rate', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->unsignedInteger('mileage')->default(0);
            $table->string('status')->default('available')->index();
            $table->date('registration_expires_at')->nullable();
            $table->date('insurance_expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
