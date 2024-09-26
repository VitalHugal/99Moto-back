<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_participation_latitudine', 'user_participation_longitudine', 'recovered_voucher'];
    protected $table = 'participation';
    protected $dates = 'deleted_at';


    public function rulesParticipation() 
    {
        return [
          'user_participation_latitudine'  => 'required|max:255',
          'user_participation_longitudine'  => 'required|max:255',
          'recovered_voucher' => 'required|boolean|in:0,1'
        ];
    }

    public function feedbackParticipation()
    {
        return [
            'user_participation_latitudine.required' => 'Campo user_participation_latitudine obrigátorio.',
            'user_participation_latitudine.max' => 'Só é possível preencher o campo com até 255 carateres.',
            'user_participation_longitudine.required' => 'Campo user_participation_longitudine obrigátorio',
            'user_participation_longitudine.max' => 'Só é possível preencher o campo com até 255 carateres.',
            'recovered_voucher.required' => 'Campo recovered_voucher obrigátorio',
            'recovered_voucher.in' => 'Válido apenas 0 ou 1 para esse campo.',
        ];
    }
}