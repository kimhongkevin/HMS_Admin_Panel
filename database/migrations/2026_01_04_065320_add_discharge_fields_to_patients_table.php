<?php

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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('discharge_status')->nullable()->after('medical_history');
            $table->timestamp('discharge_date')->nullable()->after('discharge_status');
            $table->text('discharge_notes')->nullable()->after('discharge_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['discharge_status', 'discharge_date', 'discharge_notes']);
        });
    }
};
