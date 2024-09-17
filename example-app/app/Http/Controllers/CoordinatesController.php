<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;

class CoordinatesController extends Controller
{

    protected $voucher_coordinate;

    public function __construct(VoucherCoordinate $voucher_coordinate)
    {
        $this->voucher_coordinate = $voucher_coordinate;
    }

    public function verifyCoordinates(Request $request, $id)
    {

        $coordinate = UserCoordinate::find($id);

        $latUser = $coordinate->latitudine_user; 
        $lonUser = $coordinate->longitudine_user; 
        
        function verifyDistanceVoucher()
        {
            
        }

        // Função para calcular distância entre duas coordenadas
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            $R = 6371; // Raio da Terra em km
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $distance = $R * $c; // Distância em km

            return $distance;
        }

        // Função para verificar se a distância está dentro de um raio especificado
        function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radiusInMeters)
        {
            $distanceInKm = getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2);
            $radiusInKm = $radiusInMeters / 1000; // Converter metros para km

            return $distanceInKm <= $radiusInKm;
        }

        // Exemplo de uso
        $lat1 = -23.523070712234667;
        $lon1 = -46.70959537508676;
        $lat2 = -23.522326088643396;
        $lon2 = -46.70948214847006;

        $distanceInMeters = getDistanceFromLatLonInKm(-23.523070712234667, -46.70959537508676, -23.522326088643396, -46.70948214847006) * 1000;

        $distanceInMeters = getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2) * 1000;
        $isWithinRadius = $distanceInMeters <= 100;

        // Retornar os resultados
        return response()->json([
            'Distância em metros' => $distanceInMeters,
            'Esta dentro dos 100 metros' => $isWithinRadius,

        ]);
    }

}