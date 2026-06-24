<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->foreignId('caregiver_id')
                ->nullable()
                ->after('facility_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropForeign(['caregiver_id']);
            $table->dropColumn('caregiver_id');
        });
    }
};