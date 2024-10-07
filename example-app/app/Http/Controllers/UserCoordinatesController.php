<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\NightInCities;
use App\Models\Participation;
use Illuminate\Http\Request;

use App\Models\UserCoordinate;
use App\Models\VoucherCoordinate;

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
        // recupera os dados da requisicao
        $info_latitudine = $request->user_coordinates_latitudine;
        $info_longitudine = $request->user_coordinates_longitudine;
        $local_time = $request->local_time;

        // formata latitude e longitude
        $info_latitudine_formated = explode('.', $info_latitudine);
        $info_longitudine_formated = explode('.', $info_longitudine);
        $info_local_time_formated = explode(' ', $local_time);

        // verifica se existe coordenadas na tabela de cidades 
        $verifyExistsCoordinates = NightInCities::where('city_latitudine', $info_latitudine_formated[0])
            ->where('city_longitudine', $info_longitudine_formated[0])
            ->first();

        // // verificando se a coleção está vazia
        // if ($verifyExistsCoordinates === null) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Nenhuma localização encontrada',
        //     ]);
        // }

        // //recuperando noite e dia
        // $night = $verifyExistsCoordinates->night;
        // $dayLight = $verifyExistsCoordinates->daylight;

        // //convertendo as strings de horario
        // $nightTime = \Carbon\Carbon::createFromFormat('H:i:s', $night);
        // $dayTime = \Carbon\Carbon::createFromFormat('H:i:s', $dayLight);

        // //formatando time
        // $currentTime = strtotime($info_local_time_formated[1]);
        // $nightTimeStart = strtotime($nightTime);
        // $nightTimeEnd = strtotime('23:59:59');
        // $dayTimeStart = strtotime('00:00:00');
        // $dayTimeEnd = strtotime($dayTime);

        // // comparar se o horário está entre 20:00 e 05:00
        // if (($currentTime >= $nightTimeStart && $currentTime <= $nightTimeEnd) ||
        //     ($currentTime >= $dayTimeStart && $currentTime <= strtotime($dayTime))
        // ) {
        //     // dd('aqui 1');
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Fora do horário de participação.',
        //     ]);
        // }

        //valida a requisicao
        $coordinate_user = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers2(),
            $this->coordinate_user->feedbackCoordinatesUsers2(),
        );

        //valida a requisicao
        $participation = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        // se tudo ok com a validacao cria userCoordinate
        $coordinate_user = $this->coordinate_user->create([
            'user_coordinates_latitudine' => $request->user_coordinates_latitudine,
            'user_coordinates_longitudine' => $request->user_coordinates_longitudine,
            'local_time' => $request->local_time,
        ]);

        // se tudo ok com a validacao cria participation
        $participation = $this->participation->create([
            'user_participation_latitudine' => $request->user_coordinates_latitudine,
            'user_participation_longitudine' => $request->user_coordinates_longitudine,
            'start_participation' => $request->local_time,
        ]);

        $idUser = $coordinate_user->id;

        //pega a latitude e longitude do usuario
        $latUser = $coordinate_user->user_coordinates_latitudine;
        $lonUser = $coordinate_user->user_coordinates_longitudine;

        //funçao para calcular a distancia entre duas coordenadas
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            // raio da Terra em km
            $R = 6371; 
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            // distancia em km
            return $R * $c;
        }

        //definindo o raio máximo 100 metros
        $radiusInKm = 100 / 1000;

        //procedures para retornar todos os voucher do banco de dados
        $results = DB::select('CALL GetAllVoucherCoordinates()');

        //iniciando a variavel
        $locationsWithinRadius = [];

        // foreach para recuperar as latitudes e longitudes de todos os resultados do banco de dados
        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // verifica se a distancia está dentro do limite definido em radiusInKm
            if ($distanceInKm <= $radiusInKm) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    //convertendo para metros
                    'distance_in_meters' => $distanceInKm * 1000,
                ];
            }
        }

        // se houver voucher no raio de 100 metros do usuario
        if (!empty($locationsWithinRadius)) {

            // se estiver em area promocional adiciona 1 na coluna 
            Participation::where('id', $idUser)->update(['promotional_area' => 1]);
            return response()->json([
                'success' => true,
                'message' => 'usuário em região promocional',
                'idUser' => $idUser,
            ]);
        } else {

            // senão estiver em area promocional adiciona 0 na coluna 
            Participation::where('id', $idUser)->update(['promotional_area' => 0]);
            return response()->json([
                'success' => false,
                'message' => 'usuário em região NÃO promocional',
                'idUser' => $idUser,
            ]);
        }
    }

    //endpoint para deletar userCoordinates
    public function deleteCoordinatesUsers($id)
    {
        //encontra as coordenadas do usuário
        $deleteCoordinatesUsers = $this->coordinate_user->find($id);

        if ($deleteCoordinatesUsers === null) {
            return  response()->json(['error' => "Nenhum resultado encontrado."]);
        }

        // deleta userCoordinates
        $deleteCoordinatesUsers->delete();
        return response()->json(["sucesso" => 'Coordenadas do usuário excluida com sucesso.']);
    }
}