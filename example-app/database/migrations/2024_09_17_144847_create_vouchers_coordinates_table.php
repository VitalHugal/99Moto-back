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
            $table->string('latitudine_1');
            $table->string('longitudine_1');
            $table->unsignedBigInteger('voucher_id');
            $table->boolean('recovered_voucher')->default(value: 0);
            $table->string('custom_3')->nullable();
            $table->string('custom_4')->nullable();
            $table->string('custom_5')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers_coordinates');
    }
};