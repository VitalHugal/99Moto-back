<?php

namespace Database\Factories;

use App\Models\VoucherCoordinate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoordinateFactory extends Factory
{
    protected $model = VoucherCoordinate::class;

    public function definition()
    {
        return [
            'latitudine_1' => $this->generateRandomLatitude(),
            'latitudine_2' => $this->generateRandomLatitude(),
            'longitudine_1' => $this->generateRandomLongitude(),
            'longitudine_2' => $this->generateRandomLongitude(),
        ];
    }

    private function generateRandomLatitude()
    {
        return $this->faker->latitude(-90, 90); // Usar o Faker para gerar latitude
    }

    private function generateRandomLongitude()
    {
        return $this->faker->longitude(-180, 180); // Usar o Faker para gerar longitude
    }
}