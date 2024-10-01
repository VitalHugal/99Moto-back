<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCoordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["user_coordinates_latitudine", "user_coordinates_longitudine", "local_time", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "user_coordinates";
    protected $dates = ['deleted_at'];

    public function rulesCoordinatesUsers()
    {
        return [
            'user_coordinates_latitudine' => "required|max:255",
            'user_coordinates_longitudine' => "required|max:255",
        ];
    }

    public function feedbackCoordinatesUsers()
    {
        return [
            'user_coordinates_latitudine.required' => "Campo obrigátorio",
            'user_coordinates_latitudine.max' => "Só é possível preencher o campo com até 255 carateres.",
            'user_coordinates_longitudine.required' => "Campo obrigátorio",
            'user_coordinates_longitudine.required' => "Só é possível preencher o campo com até 255 carateres.",
        ];
    }

    public function rulesCoordinatesUsers2()
    {
        return [
            'user_coordinates_latitudine' => "required|max:255",
            'user_coordinates_longitudine' => "required|max:255",
            'local_time' => "required",
        ];
    }

    public function feedbackCoordinatesUsers2()
    {
        return [
            'user_coordinates_latitudine.required' => "Campo obrigátorio",
            'user_coordinates_latitudine.max' => "Só é possível preencher o campo com até 255 carateres.",
            'user_coordinates_longitudine.required' => "Campo obrigátorio",
            'user_coordinates_longitudine.required' => "Só é possível preencher o campo com até 255 carateres.",
            'local_time.required' => "Campo obrigátorio",
        ];
    }
}