<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coordinates;

class CoordinatesController extends Controller
{

    protected $coordinates;

    public function __construct(Coordinates $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * 
     * Display a listing of the resource.
     */
    public function verifyCoordinates()
    {
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            $R = 6371; // Raio da Terra em km
            $dLat = deg2rad($lat2 - $lat1); // Converter diferença de latitude para radianos
            $dLon = deg2rad($lon2 - $lon1); // Converter diferença de longitude para radianos

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $d = $R * $c; // Distância em km

            return $d;
        }

        // Exemplo de uso:
        $distancia = getDistanceFromLatLonInKm(51.5074, -0.1278, 40.7128, -74.0060);
        echo "Distância: " . $distancia . " km";
    }
}