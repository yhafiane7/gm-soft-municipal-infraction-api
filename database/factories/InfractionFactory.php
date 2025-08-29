<?php

namespace Database\Factories;
use App\Models\Infraction;
use App\Models\Commune;
use App\Models\Violant;
use App\Models\Agent;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Infraction>
 */
class InfractionFactory extends Factory
{
    protected $model= Infraction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $commune = Commune::inRandomOrder()->first();
        $violant = Violant::inRandomOrder()->first();
        $agent = Agent::inRandomOrder()->first();
        $categorie = Categorie::inRandomOrder()->first();

        return [
            //
            'nom'=>$this->faker->numerify('infraction-####'),
            'date'=>$this->faker->date(),
            'adresse'=>$this->faker->address(),
            'commune_id'   => $commune->id,
            'violant_id'   => $violant->id,
            'agent_id'     => $agent->id,
            'categorie_id' => $categorie->id,
            'latitude'     => $this->faker->latitude(),
            'longitude'    => $this->faker->longitude()
        ];
    }
}
