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
        Schema::table('leaders', function (Blueprint $table) {
            // Add new columns to store the start and end of the leader's life.
            $table->string('life_start', 20)->nullable()->after('lifespan');
            $table->string('life_end', 20)->nullable()->after('life_start');

            // Remove the old lifespan column:
            $table->dropColumn('lifespan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaders', function (Blueprint $table) {
            // Recreate the lifespan column
            $table->string('lifespan', 255)->nullable()->after('civilization_id');

            // Drop the new columns.
            $table->dropColumn(['life_start', 'life_end']);
        });
    }
};
