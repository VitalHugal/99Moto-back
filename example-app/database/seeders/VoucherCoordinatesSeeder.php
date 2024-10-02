<?php

namespace Database\Seeders;

use App\Models\VoucherCoordinate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VoucherCoordinate::insert([
            [
                //bizsys-teste
                'latitudine_1' => '-23.522656785035263',
                'longitudine_1' => '-46.709297929225315',
                // 'voucher_id' => '1',
            ],
            [
                //Estação Jardim Silveira
                'latitudine_1' => '-23.5236786',
                'longitudine_1' => '-46.8939034',
                // 'voucher_id' => '2',
            ],
            [
                //Estação Calmon Viana
                'latitudine_1' => '-23.5254483',
                'longitudine_1' => '-46.3334494',
                // 'voucher_id' => '3',
            ],
            [
                //Estação Brás Cubas
                'latitudine_1' => '-23.5363155',
                'longitudine_1' => '-46.2277955',
                // 'voucher_id' => '4',
            ],
            [
                //Fatec - Faculdade de Tecnologia de Carapicuíba
                'latitudine_1' => '-23.5170802',
                'longitudine_1' => '-46.8379756',
                // 'voucher_id' => '5',
            ],
            [
                //UNINOVE - Campus Osasco
                'latitudine_1' => '-23.5334248',
                'longitudine_1' => '-46.7796853',
                // 'voucher_id' => '6',
            ],
            [
                //Shopping Iguatemi Alphaville - Barueri
                'latitudine_1' => '-23.5045588',
                'longitudine_1' => '-46.8509371',
                // 'voucher_id' => '7',
            ],
            [
                //Shopping Tamboré - Barueri
                'latitudine_1' => '-23.5043534',
                'longitudine_1' => '-46.8369255',
                // 'voucher_id' => '8',
            ],
            [
                //Estação Carapicuíba
                'latitudine_1' => '-23.5187066',
                'longitudine_1' => '-46.8382247',
                // 'voucher_id' => '9',
            ],
            [
                //Estação Franco da Rocha
                'latitudine_1' => '-23.3296758',
                'longitudine_1' => '-46.7289516',
                // 'voucher_id' => '10',
            ],
            [
                //Estação General Miguel Costa
                'latitudine_1' => '-23.5235508',
                'longitudine_1' => '-46.8178711',
                // 'voucher_id' => '11',
            ],
            
        ]);
    }
}