<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Document;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        foreach (['fr' => 'Loi', 'ar' => 'Décret'] as $lang => $category) {
            Document::factory()->count(5)->create([
                'language' => $lang,
                'category' => $category,
                'indexed_data' => json_encode(['text' => 'Contenu indexé exemple']),
                'file_path' => 'files/sample.pdf',
                'summary' => 'Résumé du document en ' . $lang,
                'title' => 'Document en ' . strtoupper($lang),
                'publish_date' => now(),
                'filename' => 'sample.pdf',
            ]);
        }
    }
}

