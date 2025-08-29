<?php

namespace Database\Factories;
use App\Models\Violant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Violant>
 */
class ViolantFactory extends Factory
{
    protected $model= Violant::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cin_letters = $this->faker->randomLetter(2);
        $cin_numbers = $this->faker->unique()->randomNumber(6);
        $cin = $cin_letters.$cin_numbers;
        return [
            //
            'nom'=> $this->faker->lastName(),
            'prenom'=>$this->faker->firstName(),
            'cin'=>$cin
        ];
    }
}
