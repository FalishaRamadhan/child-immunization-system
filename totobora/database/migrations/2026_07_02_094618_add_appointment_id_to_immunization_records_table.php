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
    Schema::table('immunization_records', function (Blueprint $table) {
        $table->unsignedBigInteger('appointment_id')->nullable()->after('child_id');

        $table->unique('appointment_id');

        $table->foreign('appointment_id')
            ->references('appointment_id')
            ->on('appointments')
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('immunization_records', function (Blueprint $table) {
        $table->dropForeign(['appointment_id']);
        $table->dropUnique(['appointment_id']);
        $table->dropColumn('appointment_id');
    });
}
};
