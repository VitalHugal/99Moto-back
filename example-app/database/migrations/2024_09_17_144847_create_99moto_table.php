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
        Schema::create('99moto_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string('latitudine-1');
            $table->string('longitudine-1');
            $table->string('latitudine-2');
            $table->string('longitudine-2');
            $table->string('custom-1');
            $table->string('custom-2');
            $table->string('custom-3');
            $table->string('custom-4');
            $table->string('custom-5');
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