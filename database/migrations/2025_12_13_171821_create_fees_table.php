<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Heart Surgery", "Paracetamol"
            $table->string('code')->unique(); // e.g., "HS-001", "MED-PAR-001"
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2); // Fee amount
            $table->string('unit')->default('per service'); // per service, per day, per item, per test
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
