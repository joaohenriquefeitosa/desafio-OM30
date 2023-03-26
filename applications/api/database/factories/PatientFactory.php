<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'picture' => $this->faker->imageUrl(),
            'name' => $this->faker->name(),
            'mother_name' => $this->faker->name(),
            'birth_date' => $this->faker->date(),
            'cpf' => $this->faker->unique()->numerify('###########'),
            'cns' => $this->faker->unique()->numerify('###########'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Patient $patient) {
            $patient->address()->save(Address::factory()->make());
        });
    }
}
