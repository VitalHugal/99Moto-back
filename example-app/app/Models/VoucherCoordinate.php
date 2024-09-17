<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["latitudine_1", "longitudine_1", "latitudine_2", "longitudine_2", "custom_1", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "vouchers_coordinates";
    
    public function rulesCoordinatesUsers()
    {
        return [
            'custom_1' => "required",
            'custom_2' => "required"
        ];
    }

    public function feedbackCoordinatesUsers()
    {
        return [
           'custom_1.required' => "Campo obrigátorio",
           'custom_2.required' => "Campo obrigátorio"
        ];
    }
}