<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'date' => $this->faker->date(),
            'language' => $this->faker->randomElement(['fr', 'ar']),
            'file_path' => 'files/sample.pdf', // Static for now or use Storage fake
            'summary' => $this->faker->paragraph(2),
            'category' => $this->faker->randomElement(['Loi', 'Décret', 'Arrêté']),
            'content' => $this->faker->text(1000),
        ];
    }
}

