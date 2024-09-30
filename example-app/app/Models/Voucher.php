<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['voucher', 'recovered_voucher', 'custom-1', 'custom-2', 'custom-3'];
    protected $table = 'vouchers';
    protected $dates = ['deleted_at'];

    public function rules()
    {
        return [
            'voucher' => 'required|max:6',
            'recovered_voucher' => 'boolean|in:0,1'
        ];
    }

    public function feedback()
    {
        return [
            'voucher.required' => 'Campo obrigatório.',
            'voucher.max' => 'O cupom deve ter até 6 caracteres.',
            'recovered_voucher.boolean' => 'Só é válidos para esse campo 0 ou 1',
        ];
    }
}