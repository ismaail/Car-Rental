<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('file_path');
            $table->string('document_number')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_documents');
    }
};
