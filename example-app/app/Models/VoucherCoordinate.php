<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['latitudine_1', 'longitudine_1', 'voucher_id', 'recovered_voucher','custom_3', 'custom_4', 'custom_5'];
    protected $table = "vouchers_coordinates";
    protected $dates = ['deleted_at'];


    public function rulesCoordinatesVouchers()
    {
        return [
            'latitudine_1' => "required",
            'longitudine_1' => "required",
            'voucher_id' => 'required|integer',
            'recovered_voucher' => 'boolean|in:0,1'
        ];
    }

    public function feedbackCoordinatesVouchers()
    {
        return [
            'latitudine_1.required' => "Campo é obrigátorio.",
            'longitudine_1.required' => "Campo é obrigátorio.",
            'voucher_id.required' => "Campo é obrigátorio.",
            'voucher_id.integer' => "Válido apenas valores númericos inteiros para esse campo.",
            'recovered_voucher.boolean' => 'Só é válidos para esse campo 0 ou 1',
        ];
    }
}