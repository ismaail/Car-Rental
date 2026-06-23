<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->index();
            $table->string('email')->nullable()->index();
            $table->string('national_id_number')->nullable()->index();
            $table->string('passport_number')->nullable()->index();
            $table->string('driving_license_number')->nullable()->index();
            $table->date('driving_license_expires_at')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Morocco');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
