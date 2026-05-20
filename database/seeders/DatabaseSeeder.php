<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Peserta Utama (Gunakan ini untuk Login)
        User::create([
            'name' => 'Peserta Try Out',
            'email' => 'peserta@test.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Kategori Utama sesuai Standar BKN
        Category::create(['name' => 'TWK', 'passing_grade' => 65]);
        Category::create(['name' => 'TIU', 'passing_grade' => 80]);
        Category::create(['name' => 'TKP', 'passing_grade' => 166]);

        // 3. Buat Wadah Paket Try Out Edisi 2022
        Package::create([
            'title' => 'Try Out SKD CPNS Premium Edisi 2022',
            'duration' => 100, // 100 menit standar BKN
            'is_active' => true,
        ]);

        // 4. Panggil Seeder Khusus Ekstrak PDF otomatis
        $this->call([
            QuestionFromPdfSeeder::class,
        ]);
    }
}