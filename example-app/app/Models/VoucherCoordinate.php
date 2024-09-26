<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["latitudine_1", "longitudine_1", "qtn_cupons", "cupom", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "vouchers_coordinates";
    protected $dates = ['deleted_at'];
   
    public function rulesCoordinatesVouchers()
    {
        return [
            'latitudine_1' => "required",
            'longitudine_1' => "required",
            'qtn_cupons' => 'required|integer',
            'cupom' => 'required|max:6',
        ];
    }

    public function feedbackCoordinatesVouchers()
    {
        return [
           'latitudine_1.required' => "Latitude é obrigátorio.",
           'longitudine_1.required' => "Longitude é obrigátorio.",
           'qtn_cupons.required' => "qtn_cupons é obrigátorio.",
           'cupom.required' => "cupom é obrigátorio.",
           'cupom.max' => "cupom deve ter no máximo 6 caracteres.",
           'qtn_cupons.integer' => "Válido apenas valores númericos inteiros para esse campo.",
        ];
    }
}