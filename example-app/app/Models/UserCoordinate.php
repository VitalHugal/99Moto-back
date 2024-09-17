<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["latitudine_user", "longitudine_user", "custom_1", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "user_coordinates";
    
    public function rulesCoordinatesUsers()
    {
        return [
            'latitudine_user' => "required",
            'longitudine_user' => "required"
        ];
    }

    public function feedbackCoordinatesUsers()
    {
        return [
           'latitudine_user.required' => "Campo obrigátorio",
           'longitudine_user.required' => "Campo obrigátorio"
        ];
    }
}