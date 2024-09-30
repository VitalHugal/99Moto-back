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

    public function insertVoucherCupons(Request $request)
    {
        $voucher_cupons = $request->validate(
            $this->voucher_cupons->rules(),
            $this->voucher_cupons->feedback()
        );

        $voucher_cupons = $this->voucher_cupons->create([
            'voucher' => $request->voucher,
        ]);

        return response()->json($voucher_cupons);
    
    }
}