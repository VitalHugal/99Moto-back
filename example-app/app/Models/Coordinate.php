<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coordinates extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["latitudine-1", "longitudine-1", "latitudine-2", "longitudine-2", "custom-1", "custom-2", "custom-3", "custom-4", "custom-5"];


    public function rules()
    {
        return [
            "latitudine-1" => 'required',
            "longitudine-1" => 'required',
            "latitudine-2" => 'required',
            "longitudine-2" => 'required',
        ];
    }

    public function feedback()
    {
        return [
            'latitudine-1.required' => 'Campo latitude obrigátorio.',
            'longitudine-1.required' => 'Campo longitude obrigátorio.',
            'latitudine-2.required' => 'Campo latitude obrigátorio.',
            'longitudine-2.required' => 'Campo longitude obrigátorio.',
        ];
    }
}