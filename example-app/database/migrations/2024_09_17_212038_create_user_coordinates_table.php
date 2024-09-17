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
        Schema::create('user_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string("latitudine_user");
            $table->string("longitudine_user");
            $table->string("custom_1");
            $table->string("custom_2");
            $table->string("custom_3");
            $table->string("custom_4");
            $table->string("custom_5");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coordinates');
    }
};