<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;
use App\Models\Participation;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class VoucherCoordinatesController extends Controller
{

    protected $voucher_coordinate;

    public function __construct(VoucherCoordinate $voucher_coordinate)
    {
        $this->voucher_coordinate = $voucher_coordinate;
    }

    // endpoint para inserir localização dos vouchers
    public function insertVoucherCoordinates(Request $request)
    {
        // valida a requisição
        $voucher_coordinate = $request->validate(
            $this->voucher_coordinate->rulesCoordinatesVouchers(),
            $this->voucher_coordinate->feedbackCoordinatesVouchers()
        );

        // se tudo ok com a validação cria voucherCoordinate
        $voucher_coordinate = $this->voucher_coordinate->create([
            'latitudine_1' => $request->latitudine_1,
            'longitudine_1' => $request->longitudine_1,
            'voucher_id' => $request->voucher_id,
        ]);

        return response()->json($voucher_coordinate);
    }

    // endpoint para recuperar voucher
    public function getVouchers($id)
    {
        // Encontra as coordenadas do usuário
        $coordinate = UserCoordinate::find($id);

        // se não encontrado o id informado na requisição retorna false
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

        // procedures para retornar todos os voucher do banco de dados cujo não tenha sido recuperados
        $results = DB::select('CALL GetAllVoucherCoordinatesWhereRecoveredVouchers()');

        // iniciando a variavel
        $locationsWithinRadius = [];

        // foreach para recuperar as latitudes e longitudes de todos os resultados do banco de dados
        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // Calcula a distância entre o usuário e a localização atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // Verifica se a distância está dentro do limite definido se sim retorna info da localização do voucher
            if ($distanceInKm <= $radiusInKm) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    'latitudine_1' => $location->latitudine_1,
                    'longitudine_1' => $location->longitudine_1,
                    'voucher_id' => $location->voucher_id,
                    'distance_in_meters' => $distanceInKm * 1000, // Convertendo para metros
                ];
            }
        }

        // se não houver voucher no raio de 100 metros do usuario ou vouchers esgostados
        if (empty($locationsWithinRadius)) {

            // adiciona na tabela participação que voucher NÂO foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 0]);

            return response()->json([
                'success' => false,
                'message' => 'Não esta na area promocional ou vouchers esgotados.'
            ]);
        }

        // recupera o primeiro id de localização com voucher
        $voucher = array_column($locationsWithinRadius, 'voucher_id');
        $firstVoucher = $voucher[0];

        // recupera o id da localização do voucher
        $idVoucher = array_column($locationsWithinRadius, 'id');

        // resgantando o voucher
        $voucher = Voucher::where('id', $firstVoucher)->get('voucher');

        // se tiver voucher
        if (!empty($locationsWithinRadius)) {

            // adiciona na tabela participação que voucher foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 1]);

            // deletando o id que tem como referncia o voucher_id
            VoucherCoordinate::where('id', $idVoucher)->update(['recovered_voucher' => 1]);

            // deletando o voucher que tem como referncia o id
            Voucher::where('id', $idVoucher)->update(['recovered_voucher' => 1]);

            return response()->json([
                'success' => true,
                'message' => $voucher,
            ]);
        }
    }
}