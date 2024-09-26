<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;
use App\Models\Participation;

use Illuminate\Support\Facades\DB;

class VoucherCoordinatesController extends Controller
{

    protected $voucher_coordinate;

    public function __construct(VoucherCoordinate $voucher_coordinate)
    {
        $this->voucher_coordinate = $voucher_coordinate;
    }

    public function insertVoucherCoordinates(Request $request)
    {
        $voucher_coordinate = $request->validate(
            $this->voucher_coordinate->rulesCoordinatesVouchers(),
            $this->voucher_coordinate->feedbackCoordinatesVouchers()
        );

        $voucher_coordinate = $this->voucher_coordinate->create([
            'latitudine_1' => $request->latitudine_1,
            'longitudine_1' => $request->longitudine_1,
            'qtn_voucher' => $request->qtn_voucher,
            'voucher' => $request->voucher,
        ]);

        return response()->json($voucher_coordinate);
    }

    public function verifyCoordinates($id)
    {
        // Encontra as coordenadas do usuário
        $coordinate = UserCoordinate::find($id);

        $participation = Participation::find($id);

        if ($coordinate === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado encontrado'
            ]);
        }

        // Pega a latitude e longitude do usuário
        $latUser = $coordinate->user_coordinates_latitudine;
        $lonUser = $coordinate->user_coordinates_longitudine;

        // Função para calcular a distância entre duas coordenadas
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            $R = 6371; // Raio da Terra em km
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            return $R * $c; // Distância em km
        }

        // Definindo o raio máximo (100 metros)
        $radiusInKm = 100 / 1000; // 100 metros convertido para quilômetros

        //Buscar todas as coordenadas no banco de dados
        //$AllVouchersCoordinates = VoucherCoordinate::all();

        $results = DB::select('CALL GetAllVoucherCoordinates()');

        //$voucherLocalization = VoucherCoordinate::where('latitudine_1', 'LIKE', $latUserformat . '%')
        //    ->where('longitudine_1', 'LIKE', $lonUserformat . '%')
        //    ->get();

        $locationsWithinRadius = [];

        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // Calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // Verifica se a distância está dentro do limite definido em radiusInKm
            if (
                $distanceInKm
                <= $radiusInKm
            ) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    'latitudine_1' => $location->latitudine_1,
                    'longitudine_1' => $location->longitudine_1,
                    'voucher' => $location->voucher,
                    'qtn_voucher' => $location->qtn_voucher,
                    'qtn_voucher_recovered' => $location->qtn_voucher_recovered,
                    'distance_in_meters' => $distanceInKm * 1000, // Convertendo para metros
                ];
            }
        }

        $qtn_voucher = array_column($locationsWithinRadius, 'qtn_voucher');
        $qtn_voucher_recovered = array_column($locationsWithinRadius, 'qtn_voucher_recovered');
        
        if ($qtn_voucher_recovered === $qtn_voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado encontrado.'
            ]);
        }

        $voucher = array_column($locationsWithinRadius, 'voucher');        
        
        // Se encontrar localização
        if (!empty($locationsWithinRadius)) {

            $participation = Participation::where('id', $id)->update(['recovered_voucher' => 1]);

            return response()->json([
                'success' => true,
                'message' => $voucher,
            ]);
        } else {

            $participation = Participation::where('id', $id)->update(['recovered_voucher' => 0]);

            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado encontrado'
            ]);
        }
    }

    public function userGetVoucher($id)
    {
        $voucher = $this->voucher_coordinate->find($id);

        if ($voucher === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado encontrado'
            ]);
        }

        if ($voucher->qtn_cupons_recovered === $voucher->qtn_cupons) {
            return response()->json([
                'success' => false,
                'message' => 'Vouchers se esgotaram nessa região.'
            ]);
        }

        // dd();
        // // Deletar o voucher
        // $voucher->delete();

        // // Retornar o voucher para o usuário
        // return response()->json([
        //     'message' => 'Voucher obtido com sucesso.',
        //     'voucher' => $voucherDetails
        // ]);
    }
}