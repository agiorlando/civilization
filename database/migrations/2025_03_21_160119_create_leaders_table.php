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
        Schema::create('leaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('civilization_id')
                  ->constrained('civilizations')
                  ->onDelete('cascade');
            $table->string('name');
            $table->string('icon');
            $table->string('subtitle')->nullable();
            $table->string('lifespan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaders');
    }
};
