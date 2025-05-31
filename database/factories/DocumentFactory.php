<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
public function definition(): array
{
    return [
        'title' => $this->faker->sentence,
        'date' => $this->faker->date(),
        'publish_date' => $this->faker->date(),
        'summary' => $this->faker->paragraph,
        'content' => $this->faker->text(500),
        'indexed_data' => json_encode([
            'keywords' => $this->faker->words(5),
            'summary' => $this->faker->sentence,
        ]), // ✅ Proper JSON
        'language' => $this->faker->randomElement(['fr', 'ar']),
        'category' => $this->faker->randomElement(['loi', 'décret', 'arrêté']),
        'file_path' => 'files/sample.pdf',
        'filename' => $this->faker->slug . '.pdf',
    ];
}


}

