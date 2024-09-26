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

        //se não encontrado o id informado na requisição
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

        // recuperando total de vouchers
        $qtn_voucher = array_column($locationsWithinRadius, 'qtn_voucher');
        // recuperando total de vouchers recuperados
        $qtn_voucher_recovered = array_column($locationsWithinRadius, 'qtn_voucher_recovered');
        // recupera o voucher 
        $voucher = array_column($locationsWithinRadius, 'voucher');
        // recupera o id do voucher
        $idVoucher = array_column($locationsWithinRadius, 'id');

        // Se houver voucher no raio de 100 metros do usuario
        if (empty($locationsWithinRadius) || $qtn_voucher_recovered >= $qtn_voucher) {

            // adiciona na tabela participação que voucher NÂO foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 0]);

            return response()->json([
                'success' => false,
                'message' => 'Não esta na area promocional ou vouchers esgotados.'
            ]);
        }
        if (!empty($locationsWithinRadius)) {
            // adiciona na tabela participação que voucher foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 1]);

            // adiciona na qunatidade de voucher resgatados mais um 
            VoucherCoordinate::where('id', $idVoucher)->increment('qtn_voucher_recovered');

            return response()->json([
                'success' => true,
                'message' => $voucher,
            ]);
        }
    }
}