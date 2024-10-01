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

    //endpoint para inserir coordenadas do usuário
    public function coordinatesUsers(Request $request)
    {
        // recupera os dados da requisição
        $info_latitudine = $request->user_coordinates_latitudine;
        $info_longitudine = $request->user_coordinates_longitudine;
        $local_time = $request->local_time;

        // formata latitude e longitude e pega os 3 primeiros caracteres ex(-23/-46) 
        $info_latitudine_formated = explode('.', $info_latitudine);
        $info_longitudine_formated = explode('.', $info_longitudine);

        // verifica se existe coordenadas na tabela de cidades 
        $verifyExistsCoordinates = NightInCities::where('city_latitudine', $info_latitudine_formated[0])
            ->where('city_longitudine', $info_longitudine_formated[0])
            ->first();

        // verificando se a coleção está vazia
        if ($verifyExistsCoordinates === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma localização encontrada',
            ]);
        }

        $night = $verifyExistsCoordinates->night;
        $dayLight = $verifyExistsCoordinates->daylight;

        // convertendo as strings de horário para objetos de data/hora
        $local_time = \Carbon\Carbon::createFromFormat('H:i:s', $local_time);
        $nightTime = \Carbon\Carbon::createFromFormat('H:i:s', $night);
        $dayTime = \Carbon\Carbon::createFromFormat('H:i:s', $dayLight);


        if (($local_time >= $nightTime && $local_time <= '23:59') || ($local_time >= '00:00' && $local_time <= $dayTime)) {
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Fora do horário de participação.',
            ]);
        }

        // valida a requisição
        $coordinate_user = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers2(),
            $this->coordinate_user->feedbackCoordinatesUsers2(),
        );

        // valida a requisição
        $participation = $request->validate(
            $this->coordinate_user->rulesCoordinatesUsers(),
            $this->coordinate_user->feedbackCoordinatesUsers()
        );

        // se tudo ok com a validação cria userCoordinate
        $coordinate_user = $this->coordinate_user->create([
            'user_coordinates_latitudine' => $request->user_coordinates_latitudine,
            'user_coordinates_longitudine' => $request->user_coordinates_longitudine,
            'local_time' => $request->local_time,
        ]);

        // se tudo ok com a validação cria participation
        $participation = $this->participation->create([
            'user_participation_latitudine' => $request->user_coordinates_latitudine,
            'user_participation_longitudine' => $request->user_coordinates_longitudine,
            'start_participation' => $request->local_time,
        ]);

        // recupera o id do usuário criado
        $idUser = $coordinate_user->id;

        // pega a latitude e longitude do usuário
        $latUser = $coordinate_user->user_coordinates_latitudine;
        $lonUser = $coordinate_user->user_coordinates_longitudine;

        // função para calcular a distância entre duas coordenadas
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            $R = 6371; // Raio da Terra em km
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            // distância em km
            return $R * $c;
        }

        // definindo o raio máximo 100 metros
        $radiusInKm = 100 / 1000;

        // procedures para retornar todos os voucher do banco de dados
        $results = DB::select('CALL GetAllVoucherCoordinates()');

        // iniciando a variavel
        $locationsWithinRadius = [];

        // foreach para recuperar as latitudes e longitudes de todos os resultados do banco de dados
        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // verifica se a distância está dentro do limite definido em radiusInKm
            if ($distanceInKm <= $radiusInKm) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    'distance_in_meters' => $distanceInKm * 1000, // Convertendo para metros
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

        //se não encontrado o id informado na requisição retorna false
        if ($deleteCoordinatesUsers === null) {
            return  response()->json(['error' => "Nenhum resultado encontrado."]);
        }

        // deleta userCoordinates
        $deleteCoordinatesUsers->delete();

        return response()->json(["sucesso" => 'Coordenadas do usuário excluida com sucesso.']);
    }
}