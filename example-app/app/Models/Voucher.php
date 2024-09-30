<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['voucher', 'custom-1', 'custom-2', 'custom-3'];
    protected $table = 'vouchers';
    protected $dates = ['deleted_at'];

    public function rules()
    {
        return [
            'voucher' => 'required|max:6',
        ];
    }

    public function feedback()
    {
        return [
            'voucher.required' => 'Campo obrigatório.',
            'voucher.max' => 'O cupom deve ter até 6 caracteres.',
        ];
    }
}