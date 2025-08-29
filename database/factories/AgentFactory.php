<?php

namespace Database\Factories;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    protected $model = Agent::class;
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
            'nom'=> $this->faker->lastName(),
            'prenom'=>$this->faker->firstName(),
            'tel'=>$this->faker->phoneNumber(),
            'cin'=>$cin


        ];
    }
   
}
