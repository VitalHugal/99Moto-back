<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            'CREATE PROCEDURE IF NOT EXISTS GetAllVoucherCoordinatesWhereRecoveredVouchers()
        BEGIN
            SELECT * 
            FROM vouchers_coordinates
            WHERE recovered_voucher = 0;
        END;'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(
            'DROP PROCEDURE IF EXISTS GetAllVoucherCoordinatesWhereRecoveredVouchers;'
        );
    }
};