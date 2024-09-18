<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;

class VoucherCoordinatesController extends Controller
{

    protected $voucher_coordinate;

    public function __construct(VoucherCoordinate $voucher_coordinate)
    {
        $this->voucher_coordinate = $voucher_coordinate;
    }

    public function insertVoucherCoordinates(Request $request)
    {
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
            return response()->json(['message' => 'Coordenada não encontrada'], 404);
        }

        // Pega a latitude e longitude do usuário
        $latUser = $coordinate->latitudine_user;
        $lonUser = $coordinate->longitudine_user;

        // $latUserformat = number_format($latUser, 2);
        // $lonUserformat = number_format($lonUser, 2);

        // Formata a latitude e longitude sem arredondar
        $latUserformat = substr($latUser, 0, strpos($latUser, '.') + 3); // 2 casas decimais
        $lonUserformat = substr($lonUser, 0, strpos($lonUser, '.') + 3); // 2 casas decimais

        //dd($latUserformat, $lonUserformat);

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
        // $radiusInKm = 100 / 1000; // 100 metros convertido para quilômetros

        // Buscar todas as coordenadas no banco de dados
        //$AllVouchersCoordinates = VoucherCoordinate::all();
        // $getAllLonVouchersCoordinates = VoucherCoordinate::where('longitudine_1', 'LIKE', $lonUserformat . '%')->get();

        $voucherLocalization = VoucherCoordinate::where('latitudine_1', 'LIKE', $latUserformat . '%')
            ->where('longitudine_1', 'LIKE', $lonUserformat . '%')
            ->get();

        $locationsWithinRadius = [];

        foreach ($voucherLocalization as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // Calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // Verifica se a distância está dentro do limite de 100 metros
            if ($distanceInKm 
            // <= $radiusInKm
            ) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    // 'latitude_voucher' => $latDb,
                    // 'longitude_voucher' => $lonDb,
                    'distance_in_meters' => $distanceInKm * 1000 // Convertendo para metros
                ];
            }
        }

        // Se encontrar alguma localização
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

    // public function verifyCoordinates(Request $request, $id)
    // {

    //     // Encontra as coordenadas do usuário
    //     $coordinate = UserCoordinate::find($id);

    //     if ($coordinate === null) {
    //         return response()->json(['error' => 'Coordenada não encontrada']);
    //     }

    //     // Pega a latitude e longitude do usuário
    //     $latUser = $coordinate->latitudine_user;
    //     $lonUser = $coordinate->longitudine_user;


    //     // Função para calcular distância entre duas coordenadas
    //     function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
    //     {
    //         $R = 6371; // Raio da Terra em km
    //         $dLat = deg2rad($lat2 - $lat1);
    //         $dLon = deg2rad($lon2 - $lon1);

    //         $a = sin($dLat / 2) * sin($dLat / 2) +
    //             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    //             sin($dLon / 2) * sin($dLon / 2);

    //         $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    //         $distance = $R * $c; // Distância em km

    //         return $distance;
    //     }

    //     // Função para verificar se a distância está dentro de um raio especificado
    //     function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radiusInMeters)
    //     {
    //         $distanceInKm = getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2);
    //         $radiusInKm = $radiusInMeters / 1000; // Converter metros para km

    //         return $distanceInKm <= $radiusInKm;
    //     }

    //     // Exemplo de uso
    //     $lat1 = -23.523070712234667;
    //     $lon1 = -46.70959537508676;
    //     $lat2 = -23.522326088643396;
    //     $lon2 = -46.70948214847006;

    //     $distanceInMeters = getDistanceFromLatLonInKm(-23.523070712234667, -46.70959537508676, -23.522326088643396, -46.70948214847006) * 1000;

    //     $distanceInMeters = getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2) * 1000;
    //     $isWithinRadius = $distanceInMeters <= 100;

    //     // Retornar os resultados
    //     return response()->json([
    //         'Distância em metros' => $distanceInMeters,
    //         'Esta dentro dos 100 metros' => $isWithinRadius,

    //     ]);
    // }
}