<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['voucher', 'custom_1', 'custom_2', 'custom_3'];
    protected $table = ['vouchers'];
    protected $dates = ['deleted_at'];

    public function voucher()
    {
        return $this->belongsTo(VoucherCoordinate::class);
    }
}
