<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coordinate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["latitudine_1", "longitudine_1", "latitudine_2", "longitudine_2", "custom_1", "custom_2", "custom_3", "custom_4", "custom_5"];
    protected $table = "99moto_coordinates";

    public function rules()
    {
        return [
            "latitudine_1" => '',
            "longitudine_1" => '',
            "latitudine_2" => '',
            "longitudine_2" => '',
        ];
    }

    public function feedback()
    {
        return [
            'latitudine_1.required' => 'Campo latitude obrigátorio.',
            'longitudine_1.required' => 'Campo longitude obrigátorio.',
            'latitudine_2.required' => 'Campo latitude obrigátorio.',
            'longitudine_2.required' => 'Campo longitude obrigátorio.',
        ];
    }
}