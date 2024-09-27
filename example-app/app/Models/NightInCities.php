<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NightInCities extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['UF','city','city_latitudine','city_longitudine','night'];
    protected $table = 'night_in_cities';
    protected $dates = 'deleted_at';
}