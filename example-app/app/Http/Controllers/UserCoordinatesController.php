<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\NightInCities;
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
        $info_latitudine = $request->user_coordinates_latitudine;
        $info_longitudine = $request->user_coordinates_longitudine;
        $local_time = $request->local_time;

        $info_latitudine_formated = substr($info_latitudine, 0, 3);
        $info_longitudine_formated = substr($info_longitudine, 0, 3);

        $verifyExistsCoordinates = NightInCities::where('city_latitudine', 'LIKE', $info_latitudine_formated . '%')
            ->where('city_longitudine', 'LIKE', $info_longitudine_formated . '%')
            ->get();

        // Verificando se a coleção está vazia
        if ($verifyExistsCoordinates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma localização encontrada',
            ]);
        }

        $nightCitie = [];

        foreach ($verifyExistsCoordinates as $city) {
            $nightCitie[] = $city->night;
        }

        $night = $nightCitie[0];

        // Convertendo as strings de horário para objetos de data/hora
        $local_time = \Carbon\Carbon::createFromFormat('H:i:s', $local_time);
        $nightTime = \Carbon\Carbon::createFromFormat('H:i:s', $night);

        // Verificando se a hora local é maior ou igual à hora de anoitecer
        if ($local_time < $nightTime) {
            return response()->json([
                'success' => false,
                'message' => 'Não anoiteceu ainda.',
            ]);
        }
        
        $coordinate_user = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers2(),
            $this->coordinate_user->feedbackCoordinatesUsers2(),
        );

        $participation = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        $coordinate_user = $this->coordinate_user->create([
            'user_coordinates_latitudine' => $request->user_coordinates_latitudine,
            'user_coordinates_longitudine' => $request->user_coordinates_longitudine,
            'local_time' => $request->local_time,
        ]);

        $participation = $this->participation->create([
            'user_participation_latitudine' => $request->user_coordinates_latitudine,
            'user_participation_longitudine' => $request->user_coordinates_longitudine,
        ]);

        $idUser = $coordinate_user->id;

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
                'message' => 'usuário em região NÃO promocional',
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