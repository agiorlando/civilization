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
        Schema::create('historical_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxonomy_id'); // ID of the associated record
            $table->string('heading');
            $table->text('text');
            $table->string('type'); // Class name (e.g., App\Models\Leader or App\Models\Civilization)
            $table->timestamps();

            $table->index(['taxonomy_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_infos');
    }
};
