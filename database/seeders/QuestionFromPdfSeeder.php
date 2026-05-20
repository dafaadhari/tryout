<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionFromPdfSeeder extends Seeder
{
    public function run(): void
    {
        $package = Package::first(); 

        if (!$package) {
            $this->command->error('Paket ujian belum ada! Jalankan DatabaseSeeder utama dulu.');
            return;
        }

        $jsonPath = database_path('data/soal_cpns.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('File JSON soal tidak ditemukan di ' . $jsonPath);
            return;
        }

        $jsonData = File::get($jsonPath);
        $questionsData = json_decode($jsonData, true);

        $categories = Category::pluck('id', 'name')->toArray();

        foreach ($questionsData as $data) {
            Question::create([
                'package_id' => $package->id,
                'category_id' => $categories[$data['category_name']] ?? 1,
                'question_text' => $data['question_text'],
                'opt_a' => $data['opt_a'],
                'opt_b' => $data['opt_b'],
                'opt_c' => $data['opt_c'],
                'opt_d' => $data['opt_d'],
                'opt_e' => $data['opt_e'],
                'correct_answer' => $data['correct_answer'],
                'tkp_scores' => $data['tkp_scores'],
            ]);
        }

        $this->command->info('Berhasil menyuntikkan ' . count($questionsData) . ' soal dari file PDF/JSON!');
    }
}