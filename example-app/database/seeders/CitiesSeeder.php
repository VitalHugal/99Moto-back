<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NightInCities;


class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NightInCities::insert([
            [
                'UF' => 'SE',
                'city' => 'Aracaju ',
                'city_latitudine' => '-10',
                'city_longitudine' => '-37',
                'night' => '18:00:00',
                'daylight' => '05:00:00',
            ],
            [
                'UF' => 'PA',
                'city' => 'Belém',
                'city_latitudine' => '-1',
                'city_longitudine' => '-48',
                'night' => '18:30:00',
                'daylight' => '05:49:00',
            ],
            [
                'UF' => 'MG',
                'city' => 'Belo Horizonte',
                'city_latitudine' => '-19',
                'city_longitudine' => '-43',
                'night' => '18:30:00',
                'daylight' => '05:24:00',
            ],
            [
                'UF' => 'RR',
                'city' => 'Boa Vista',
                'city_latitudine' => '2',
                'city_longitudine' => '-60',
                'night' => '18:30:00',
                'daylight' => '05:39:00',
            ],
            [
                'UF' => 'DF',
                'city' => 'Brasília',
                'city_latitudine' => '-15',
                'city_longitudine' => '-47',
                'night' => '18:30:00',
                'daylight' => '05:40:00',
            ],
            [
                'UF' => 'MS',
                'city' => 'Campo Grande',
                'city_latitudine' => '-20',
                'city_longitudine' => '-54',
                'night' => '18:00:00',
                'daylight' => '05:06:00',
            ],
            [
                'UF' => 'MT',
                'city' => 'Cuiabá',
                'city_latitudine' => '-15',
                'city_longitudine' => '-56',
                'night' => '18:10:00',
                'daylight' => '05:14:00',
            ],
            [
                'UF' => 'PR',
                'city' => 'Curitiba ',
                'city_latitudine' => '-25',
                'city_longitudine' => '-49',
                'night' => '18:45:00',
                'daylight' => '05:42:00',
            ],
            [
                'UF' => 'SC',
                'city' => 'Florianópolis',
                'city_latitudine' => '-27',
                'city_longitudine' => '-48',
                'night' => '18:45:00',
                'daylight' => '05:38:00',
            ],
            [
                'UF' => 'CE',
                'city' => 'Fortaleza',
                'city_latitudine' => '-3',
                'city_longitudine' => '-38',
                'night' => '18:00:00',
                'daylight' => '05:08:00',
            ],
            [
                'UF' => 'GO',
                'city' => 'Goiânia',
                'city_latitudine' => '-16',
                'city_longitudine' => '-49',
                'night' => '18:40:00',
                'daylight' => '05:47:00',
            ],
            [
                'UF' => 'PB',
                'city' => 'João Pessoa',
                'city_latitudine' => '-7',
                'city_longitudine' => '-34',
                'night' => '17:45:00',
                'daylight' => '04:53:00',
            ],
            [
                'UF' => 'AP',
                'city' => 'Macapá',
                'city_latitudine' => '0',
                'city_longitudine' => '-51',
                'night' => '19:00:00',
                'daylight' => '06:00:00',
            ],
            [
                'UF' => 'AL',
                'city' => 'Maceió',
                'city_latitudine' => '-9',
                'city_longitudine' => '-35',
                'night' => '18:10:00',
                'daylight' => '04:55:00',
            ],
            [
                'UF' => 'AM',
                'city' => 'Manaus',
                'city_latitudine' => '-3',
                'city_longitudine' => '-60',
                'night' => '18:30:00',
                'daylight' => '05:35:00',
            ],
            [
                'UF' => 'RN',
                'city' => 'Natal',
                'city_latitudine' => '-5',
                'city_longitudine' => '-35',
                'night' => '17:45:00',
                'daylight' => '04:54:00',
            ],
            [
                'UF' => 'TO',
                'city' => 'Palmas',
                'city_latitudine' => '-10',
                'city_longitudine' => '-48',
                'night' => '18:30:00',
                'daylight' => '05:45:00',
            ],
            [
                'UF' => 'RS',
                'city' => 'Porto Alegre',
                'city_latitudine' => '-30',
                'city_longitudine' => '-51',
                'night' => '19:00:00',
                'daylight' => '05:47:00',
            ],
            [
                'UF' => 'RO',
                'city' => 'Porto Velho',
                'city_latitudine' => '-8',
                'city_longitudine' => '-63',
                'night' => '18:40:00',
                'daylight' => '05:48:00',
            ],
            [
                'UF' => 'PE',
                'city' => 'Recife',
                'city_latitudine' => '-8',
                'city_longitudine' => '-34',
                'night' => '17:45:00',
                'daylight' => '04:52:00',
            ],
            [
                'UF' => 'AC',
                'city' => 'Rio Branco',
                'city_latitudine' => '-9',
                'city_longitudine' => '-67',
                'night' => '18:00:00',
                'daylight' => '05:00:00',
            ],
            [
                'UF' => 'RJ',
                'city' => 'Rio de Janeiro',
                'city_latitudine' => '-22',
                'city_longitudine' => '-43',
                'night' => '18:20:00',
                'daylight' => '05:19:00',
            ],
            [
                'UF' => 'BA',
                'city' => 'Salvador',
                'city_latitudine' => '-12',
                'city_longitudine' => '-38',
                'night' => '18:00:00',
                'daylight' => '05:05:00',
            ],
            [
                'UF' => 'MA',
                'city' => 'São Luís',
                'city_latitudine' => '-2',
                'city_longitudine' => '-44',
                'night' => '18:30:00',
                'daylight' => '05:32:00',
            ],
            [
                'UF' => 'SP',
                'city' => 'São Paulo',
                'city_latitudine' => '-23',
                'city_longitudine' => '-46',
                'night' => '18:30:00',
                'daylight' => '05:33:00',
            ],
            [
                'UF' => 'PI',
                'city' => 'Teresina',
                'city_latitudine' => '-5',
                'city_longitudine' => '-42',
                'night' => '18:15:00',
                'daylight' => '05:25:00',
            ],
            [
                'UF' => 'ES',
                'city' => 'Vitória',
                'city_latitudine' => '-20',
                'city_longitudine' => '-40',
                'night' => '18:00:00',
                'daylight' => '05:30:00',
            ],
        ]);
    }
}