<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string('latitudine_1')->nullable();
            $table->string('longitudine_1')->nullable();
            $table->string('latitudine_2')->nullable();
            $table->string('longitudine_2')->nullable();
            $table->string('custom_1')->nullable();
            $table->string('custom_2')->nullable();
            $table->string('custom_3')->nullable();
            $table->string('custom_4')->nullable();
            $table->string('custom_5')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('99moto');
    }
};