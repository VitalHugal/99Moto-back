<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;

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
        ]);

        return response()->json($voucher_coordinate);
    }

    public function verifyCoordinates($id)
    {
        // Encontra as coordenadas do usuário
        $coordinate = UserCoordinate::find($id);

        if ($coordinate === null) {
            return response()->json(['message' => 'Falha ao encontrar localização do usuário.'], 404);
        }

        // Pega a latitude e longitude do usuário
        $latUser = $coordinate->latitudine_user;
        $lonUser = $coordinate->longitudine_user;

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
        
        //dd($results);

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
                    'distance_in_meters' => $distanceInKm * 1000 // Convertendo para metros
                ];
            }
        }

        // Se encontrar localização
        if (!empty($locationsWithinRadius)) {
            return response()->json([
                'status' => 'success',
                'message' => $locationsWithinRadius,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Nenhuma localização encontrada dentro de 100 metros'
            ]);
        }
    }

    public function userGetVoucher()
    {
        
    }
}