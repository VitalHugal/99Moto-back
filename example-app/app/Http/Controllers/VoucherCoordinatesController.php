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

    public function insertVoucherCoordinates(Request $request)
    {
        // valida a requisição
        $voucher_coordinate = $request->validate(
            $this->voucher_coordinate->rulesCoordinatesVouchers(),
            $this->voucher_coordinate->feedbackCoordinatesVouchers()
        );

        // se tudo ok com a validacao cria voucherCoordinate
        $voucher_coordinate = $this->voucher_coordinate->create([
            'latitudine_1' => $request->latitudine_1,
            'longitudine_1' => $request->longitudine_1,
            //'voucher_id' => $request->voucher_id,
        ]);

        return response()->json($voucher_coordinate);
    }

    public function getVouchers($id)
    {
        
        $coordinate = UserCoordinate::find($id);
        
        if ($coordinate === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado encontrado'
            ]);
        }

        // pega a localizacao do usuario
        $latUser = $coordinate->user_coordinates_latitudine;
        $lonUser = $coordinate->user_coordinates_longitudine;

        //formata para pegar os valores iniciais da localizacao
        $info_latitudine_formated = explode('.', $latUser);
        $info_longitudine_formated = explode('.', $lonUser);

        // dd($info_latitudine_formated[0], $info_longitudine_formated[0]);
        //busca o primeiro resultado de capital com base na localização do user
        $localUF = NightInCities::where('city_latitudine', $info_latitudine_formated[0])->where('city_longitudine', $info_longitudine_formated[0])->first();

        //estado
        $UF = $localUF->UF;

        //definindo os grupos com fuso horario diferente
        $UTC3 = ['DF', 'SP', 'RJ', 'MG', 'BA', 'RS', 'PR', 'SC', 'ES', 'GO', 'CE', 'MA', 'PI', 'PB', 'PE', 'AL', 'SE', 'RN'];
        $UTC4 = ['MT', 'MS', 'AM', 'RO', 'RR'];

        //pega a data e hora atual do servidor
        $date = new DateTime();

        //verifica em qual grupo se encaixa para modificar o horario de acordo com a regiao
        if (in_array($UF, $UTC3)) {
        } elseif (in_array($UF, $UTC4)) {
            $date->sub(new DateInterval('PT1H'));
        } else {
            dd('sp -2');
            $date->sub(new DateInterval('PT2H'));
        }

        //formatando a data e hora
        $formatedDate = $date->format('d-m-Y H:i:s');

        // Funcao para calcular a distancia entre duas coordenadas
        function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
        {
            //raio da Terra em km
            $R = 6371; 
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);

            $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            //distancia em km
            return $R * $c;
        }

        // Definindo o raio maximo 100 metros
        $radiusInKm = 100 / 1000;

        // procedures para retornar todos as localizacoes de vouchers do banco de dados
        $results = DB::select('CALL GetAllVoucherCoordinates()');

        $locationsWithinRadius = [];

        // foreach para recuperar as latitudes e longitudes de todos os resultados do banco de dados
        foreach ($results as $location) {
            $latDb = $location->latitudine_1;
            $lonDb = $location->longitudine_1;

            // Calcula a distancia entre o usuario e a localizacao atual
            $distanceInKm = getDistanceFromLatLonInKm($latUser, $lonUser, $latDb, $lonDb);

            // Verifica se a distancia esta dentro do limite definido se sim retorna info da localizacao do voucher
            if ($distanceInKm <= $radiusInKm) {
                $locationsWithinRadius[] = [
                    'id' => $location->id,
                    'latitudine_1' => $location->latitudine_1,
                    'longitudine_1' => $location->longitudine_1,
                    //'voucher_id' => $location->voucher_id,
                    //convertendo para metros
                    'distance_in_meters' => $distanceInKm * 1000, 
                ];
            }
        }

        //recuperando o id da localizaçao
        $idVocuherCoordinates = array_column($locationsWithinRadius, "id");

        // se não houver voucher no raio de 100 metros do usuario
        if (empty($locationsWithinRadius)) {

            // adiciona na tabela participação que voucher NÂO foi resgatado
            Participation::where('id', $id)->update(['recovered_voucher' => 0]);

            // adicionando date e hora
            Participation::where('id', $id)->update(['end_participation' => $formatedDate]);

            // adicionando null para voucher_id 
            Participation::where('id', $id)->update(['voucher_id' => null]);

            return response()->json([
                'success' => false,
                'message' => 'Não esta na área promocional (sem voucher.)'
            ]);
        }

        $voucher = Voucher::where('recovered_voucher', 0)->first();

        //se não houver retorna que acacbou os vouchers
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

            //deletando o voucher para não seu usada novamente
            Voucher::where('id', $idVoucher)->update(['recovered_voucher' => 1]);

            //adicionando +1 na localizacaoo que foi recuperado o voucher
            VoucherCoordinate::where('id', $idVocuherCoordinates)->increment('qtn_recovered_voucher');

            return response()->json([
                'success' => true,
                'message' => $cupom,
            ]);
        }
    }
}