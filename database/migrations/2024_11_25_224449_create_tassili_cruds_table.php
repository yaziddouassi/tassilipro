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
        Schema::create('tassili_cruds', function (Blueprint $table) {
            $table->id();
            $table->string('panel');
            $table->string('model');
            $table->string('label');
            $table->string('route');
            $table->string('icon');
            $table->boolean('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoggarcruds');
    }
};
