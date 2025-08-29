<?php

namespace Database\Factories;
use App\Models\Decision;
use App\Models\Infraction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Decision>
 */
class DecisionFactory extends Factory
{
    protected $model= Decision::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $infraction = Infraction::factory()->create();
        return [
            //
            'date'=>$this->faker->date(),
            'decisionprise'=>$this->faker->sentence(),
            'infraction_id'=>$infraction->id
        ];
    }
}
