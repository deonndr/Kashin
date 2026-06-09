<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
        'nisn' => $this->faker->unique()->numerify('##########'), 
        'nama_siswa' => $this->faker->name(),
        'kelas' => $this->faker->randomElement(['XII RPL 1', 'XII RPL 2', 'XII TKJ 1', 'XII TKJ 2']),
        ];
    }
}
