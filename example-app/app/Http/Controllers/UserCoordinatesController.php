<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use Illuminate\Http\Request;

use App\Models\UserCoordinate;

class UserCoordinatesController extends Controller
{
    protected $coordinate_user;
    protected $participation;

    public function __construct(UserCoordinate $coordinate_user, Participation $participation)
    {
        $this->coordinate_user = $coordinate_user;
        $this->participation = $participation;
    }

    public function coordinatesUsers(Request $request)
    {
        $coordinate_user = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        $participation = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        $coordinate_user = $this->coordinate_user->create([
            'user_coordinates_latitudine' => $request->user_coordinates_latitudine,
            'user_coordinates_longitudine' => $request->user_coordinates_longitudine,
        ]);

        $participation = $this->participation->create([
            'user_participation_latitudine' => $request->user_coordinates_latitudine,
            'user_participation_longitudine' => $request->user_coordinates_longitudine,
        ]);

        $idUser =$coordinate_user->id;

        // Pega a latitude e longitude do usuário
        $latUser = $coordinate_user->user_coordinates_latitudine;
        $lonUser = $coordinate_user->user_coordinates_longitudine;


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

            // Distância em km
            return $R * $c;
        }

        // Definindo o raio máximo 100 metros
        $radiusInKm = 100 / 1000;

        // procedures para retornar todos os voucher do banco de dados
        $results = DB::select('CALL GetAllVoucherCoordinates()');

        // iniciando a variavel
        $locationsWithinRadius = [];

        // foreach para recuperar as latitudes e longitudes de todos os resultados do banco de dados
        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // Calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // Verifica se a distância está dentro do limite definido em radiusInKm
            if ($distanceInKm <= $radiusInKm) {
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

         // Se houver voucher no raio de 100 metros do usuario
         if (!empty($locationsWithinRadius)) {
            return response()->json([
                'success' => true,
                'message' => 'usuário em região promocional',
                'idUser' => $idUser,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'usuário em região NÂO promocional',
                'idUser' => $idUser,
            ]);
        }
    }

    public function deleteCoordinatesUsers($id)
    {
        $deeleteCoordinatesUsers = $this->coordinate_user->find($id);

        if ($deeleteCoordinatesUsers === null) {
            return  response()->json(['error' => "Nenhum resultado encontrado."]);
        }

        $deeleteCoordinatesUsers->delete();

        return response()->json(["sucesso" => 'Coordenadas do usuário excluida com sucesso.']);
    }
}