<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\VoucherCoordinate;

class CoordinateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gerar 30.000 coordenadas aleatórias
        VoucherCoordinate::factory(10000)->create();

        // Exemplo de criar uma coordenada com valores aleatórios
        VoucherCoordinate::factory()->create([
            'latitudine_1' => $this->generateRandomLatitude(),
            'latitudine_2' => $this->generateRandomLatitude(),
            'longitudine_1' => $this->generateRandomLongitude(),
            'longitudine_2' => $this->generateRandomLongitude(),
        ]);
    }

    // Função para gerar uma latitude aleatória
    private function generateRandomLatitude()
    {
        return mt_rand(-90000000, 90000000) / 1000000; // Gerar latitude entre -90 e 90
    }

    // Função para gerar uma longitude aleatória
    private function generateRandomLongitude()
    {
        return mt_rand(-180000000, 180000000) / 1000000; // Gerar longitude entre -180 e 180

    }
}