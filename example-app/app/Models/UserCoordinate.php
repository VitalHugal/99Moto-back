<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["user_coordinates_latitudine", "user_coordinates_longitudine", "custom_1", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "user_coordinates";
    protected $dates = ['deleted_at'];
    
    public function rulesCoordinatesUsers()
    {
        return [
            'user_coordinates_latitudine' => "required",
            'user_coordinates_longitudine' => "required"
        ];
    }

    public function feedbackCoordinatesUsers()
    {
        return [
           'user_coordinates_latitudine.required' => "Campo obrigátorio",
           'user_coordinates_longitudine.required' => "Campo obrigátorio"
        ];
    }
}