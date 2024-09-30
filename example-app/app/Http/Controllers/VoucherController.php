<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Voucher;

class VoucherController extends Controller
{
    
    protected $voucher_cupons;

    public function __construct(Voucher $voucher_cupons)
    {
        $this->voucher_cupons = $voucher_cupons;
    }

    //endpoint para inserir voucher cupons
    public function insertVoucherCupons(Request $request)
    {
        //valida a requisição 
        $voucher_cupons = $request->validate(
            $this->voucher_cupons->rules(),
            $this->voucher_cupons->feedback()
        );

        //se tudo ok com a validação cria voucher cupom
        $voucher_cupons = $this->voucher_cupons->create([
            'voucher' => $request->voucher,
        ]);

        return response()->json($voucher_cupons);
    
    }
}