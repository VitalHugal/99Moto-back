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
            //Rio de Janeiro
            [
                //Pão de Açúcar
                'latitudine_1' => '-22.9546868',
                'longitudine_1' => '-43.1653227',
            ],
            [
                //Praia de Copacabana
                'latitudine_1' => '-22.9711',
                'longitudine_1' => '-43.1822',
            ],
            [
                //Jardim Botânico
                'latitudine_1' => '-22.9679',
                'longitudine_1' => '-43.2252',
            ],
            [
                //Maracanã
                'latitudine_1' => '-22.9121',
                'longitudine_1' => '-43.2302',
            ],
            [
                //Shopping RioSul
                'latitudine_1' => '-22.9569',
                'longitudine_1' => '-43.1775',
            ],
            [
                //Barra Shopping
                'latitudine_1' => '-22.9999',
                'longitudine_1' => '-43.3606',
            ],
            [
                //Terminal BRT Sulacap
                'latitudine_1' => '-22.8847629',
                'longitudine_1' => '-43.401615',
            ],
            [
                //Terminal BRT Santa Cruz
                'latitudine_1' => '-22.9174703',
                'longitudine_1' => '-43.6847478',
            ],
            [
                //Estação Nova Iguaçu
                'latitudine_1' => '-22.7605181',
                'longitudine_1' => '-43.4507024',
            ],
            [
                //Madureira Shopping
                'latitudine_1' => '-22.8701575',
                'longitudine_1' => '-43.3415134',
            ],

            //são paulo
            [
                //Estação Jardim Silveira
                'latitudine_1' => '-23.5236786',
                'longitudine_1' => '-46.8939034',
            ],
            [
                //Estação Calmon Viana
                'latitudine_1' => '-23.5254483',
                'longitudine_1' => '-46.3334494',
            ],
            [
                //Estação Brás Cubas
                'latitudine_1' => '-23.5363155',
                'longitudine_1' => '-46.2277955',
            ],
            [
                //Fatec - Faculdade de Tecnologia de Carapicuíba
                'latitudine_1' => '-23.5170802',
                'longitudine_1' => '-46.8379756',
            ],
            [
                //UNINOVE - Campus Osasco
                'latitudine_1' => '-23.5334248',
                'longitudine_1' => '-46.7796853',
            ],
            [
                //Shopping Iguatemi Alphaville - Barueri
                'latitudine_1' => '-23.5045588',
                'longitudine_1' => '-46.8509371',
            ],
            [
                //Shopping Tamboré - Barueri
                'latitudine_1' => '-23.5043534',
                'longitudine_1' => '-46.8369255',
            ],
            [
                //Estação Carapicuíba
                'latitudine_1' => '-23.5187066',
                'longitudine_1' => '-46.8382247',
            ],
            [
                //Estação Franco da Rocha
                'latitudine_1' => '-23.3296758',
                'longitudine_1' => '-46.7289516',
            ],
            [
                //Estação General Miguel Costa
                'latitudine_1' => '-23.5235508',
                'longitudine_1' => '-46.8178711',
            ],

        ]);
    }
}