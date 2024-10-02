<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_participation_latitudine',
        'user_participation_longitudine',
        'recovered_voucher',
        'voucher_id',
        'promotional_area',
        'start_participation',
        'end_participation'
    ];
    protected $table = 'participation';
    protected $dates = 'deleted_at';


    public function rulesParticipation()
    {
        return [
            'user_participation_latitudine'  => 'required|max:255',
            'user_participation_longitudine'  => 'required|max:255',
            'recovered_voucher' => 'boolean|in:0,1',
            'promotional_area' => 'boolean|in:0,1|nullable',
            'start_participation' => 'required',
            'end_participation' => 'required',
            'voucher_id' => 'integer|nullable',
        ];
    }

    public function feedbackParticipation()
    {
        return [
            'user_participation_latitudine.required' => 'Campo obrigátorio.',
            'user_participation_latitudine.max' => 'Só é possível preencher o campo com até 255 carateres.',
            'user_participation_longitudine.required' => 'Campo obrigátorio',
            'user_participation_longitudine.max' => 'Só é possível preencher o campo com até 255 carateres.',
            'start_participation.required' => 'Campo obrigatório.',
            'end_participation.required' => 'Campo obrigatório.',
            'voucher_id.integer' => 'Válido apenas valores inteiros.',
            'promotional_area.boolean' => 'Valido apenas 0 ou 1 para esse campo.',
            'recovered_voucher.boolean' => 'Valido apenas 0 ou 1 para esse campo.',
        ];
    }
}