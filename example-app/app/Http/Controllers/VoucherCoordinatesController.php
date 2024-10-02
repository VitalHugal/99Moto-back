<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NightInCities;
use Illuminate\Http\Request;

use App\Models\VoucherCoordinate;
use App\Models\UserCoordinate;
use App\Models\Participation;
use App\Models\Voucher;
use DateInterval;
use DateTime;
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
            //'voucher_id' => $request->voucher_id,
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

        $info_latitudine_formated = explode('.', $latUser);
        $info_longitudine_formated = explode('.', $lonUser);

        $localUF = NightInCities::where('city_latitudine', $info_latitudine_formated)->where('city_longitudine', $info_longitudine_formated)->first();

        $localUF->UF;

        $UTC3 = ['DF', 'SP', 'RJ', 'MG', 'BA', 'RS', 'PR', 'SC', 'ES', 'GO', 'CE', 'MA', 'PI', 'PB', 'PE', 'AL', 'SE', 'RN'];
        $UTC4 = ['MT', 'MS', 'AM', 'RO', 'RR'];

        $date = new DateTime();

        if ($localUF == $UTC3) {
        } elseif ($localUF == $UTC4) {
            // Subtrai 1 hora
            $date->sub(new DateInterval('PT1H'));
        } else {
            // Subtrai 2 horas
            $date->sub(new DateInterval('PT2H'));
        }

        $formatedDate = $date->format('d-m-Y H:i:s');

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

        // procedures para retornar todos as localizações de vouchers do banco de dados
        $results = DB::select('CALL GetAllVoucherCoordinates()');

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
                    //'voucher_id' => $location->voucher_id,
                    'distance_in_meters' => $distanceInKm * 1000, // Convertendo para metros
                ];
            }
        }

        $idVocuherCoordinates = array_column($locationsWithinRadius, "id");

        // se não houver voucher no raio de 100 metros do usuario ou vouchers esgostados
        if (empty($locationsWithinRadius)) {

            // adiciona na tabela participação que voucher NÂO foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 0]);

            // adicionando date e hora
            Participation::where('id', $id)->update(['end_participation' => $formatedDate]);

            // adicionando 0 para voucher_id 
            Participation::where('id', $id)->update(['voucher_id' => null]);

            return response()->json([
                'success' => false,
                'message' => 'Não esta na area promocional.'
            ]);
        }

        $voucher = Voucher::where('recovered_voucher', 0)->first();
        
        if ($voucher === null) {
            return response()->json([
                'success' => false,
                'message' => 'Vouchers esgotados.'
            ]);
        }

        $idVoucher = $voucher->id;

        $cupom = $voucher->voucher;

        // se tiver voucher
        if (!empty($locationsWithinRadius)) {

            // adiciona na tabela participação que voucher foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 1]);

            //adicionando date e hora
            Participation::where('id', $id)->update(['end_participation' => $formatedDate]);

            // adicionando voucher_id resgatado
            Participation::where('id', $id)->update(['voucher_id' => $idVoucher]);

            //deletando o voucher para não seu utilizado novamente
            Voucher::where('id', $idVoucher)->update(['recovered_voucher' => 1]);

            //adicionando quantos cupons foram recuperados em cada localização
            VoucherCoordinate::where('id', $idVocuherCoordinates)->increment('qtn_recovered_voucher');

            return response()->json([
                'success' => true,
                'message' => $cupom,
            ]);
        }
    }
}