<?php

namespace Database\Factories;
use App\Models\Commune;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commune>
 */
class CommuneFactory extends Factory
{
    protected $model = Commune::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'pachalik-circon'=>$this->faker->lexify('Pachalik-????'),
            'caidat'=>$this->faker->lexify('caidat-????'),
            'nom'=>$this->faker->lexify('commune-????'),
            'latitude'=>$this->faker->latitude(),
            'longitude'=>$this->faker->longitude()
        ];
    }
}
