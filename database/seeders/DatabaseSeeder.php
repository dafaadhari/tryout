<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Peserta Dummy
        User::create([
            'name' => 'Peserta Try Out',
            'email' => 'peserta@test.com',
            'password' => Hash::make('password'),
            // 'role' => 'user', // Jika Boss nanti menambahkan sistem role
        ]);

        // 2. Buat Kategori TWK, TIU, TKP beserta Passing Grade
        $twk = Category::create(['name' => 'TWK', 'passing_grade' => 65]);
        $tiu = Category::create(['name' => 'TIU', 'passing_grade' => 80]);
        $tkp = Category::create(['name' => 'TKP', 'passing_grade' => 166]);

        // 3. Buat Paket Try Out
        $package = Package::create([
            'title' => 'Try Out SKD CPNS Premium Batch 1',
            'duration' => 100, // 100 menit
            'is_active' => true,
        ]);

        // 4. Masukkan Contoh Soal (Contoh TWK - Nasionalisme)
        Question::create([
            'package_id' => $package->id,
            'category_id' => $twk->id,
            'question_text' => 'Nasionalisme di Indonesia muncul sebagai reaksi terhadap kolonialisme. Salah satu tonggak kebangkitan nasional yang ditandai dengan kesadaran untuk bersatu sebagai sebuah bangsa adalah...',
            'opt_a' => 'Berdirinya Budi Utomo pada tahun 1908.',
            'opt_b' => 'Sumpah Pemuda pada tahun 1928.',
            'opt_c' => 'Proklamasi Kemerdekaan pada tahun 1945.',
            'opt_d' => 'Terbentuknya Sarekat Islam pada tahun 1912.',
            'opt_e' => 'Perang Diponegoro melawan Belanda.',
            'correct_answer' => 'A',
            'discussion_text' => 'Berdirinya Budi Utomo pada 20 Mei 1908 sering dianggap sebagai awal mula kebangkitan nasional karena memelopori pergerakan yang bersifat nasional dan modern, bukan lagi kedaerahan.',
            'tkp_scores' => null, // TWK tidak memakai sistem bobot 1-5
        ]);

        // Contoh Soal (Contoh TIU - Silogisme)
        Question::create([
            'package_id' => $package->id,
            'category_id' => $tiu->id,
            'question_text' => 'Semua karyawan PT Rayyan Karya harus memakai seragam. Bakhir adalah karyawan PT Rayyan Karya. Kesimpulan yang tepat adalah...',
            'opt_a' => 'Bakhir mungkin memakai seragam.',
            'opt_b' => 'Bakhir tidak memakai seragam.',
            'opt_c' => 'Bakhir harus memakai seragam.',
            'opt_d' => 'Beberapa karyawan tidak memakai seragam.',
            'opt_e' => 'Hanya Bakhir yang memakai seragam.',
            'correct_answer' => 'C',
            'discussion_text' => 'Silogisme Modus Ponens: Premis 1 (Semua P adalah Q), Premis 2 (X adalah P). Kesimpulan: X adalah Q. Karena Bakhir adalah karyawan, maka Bakhir harus memakai seragam.',
            'tkp_scores' => null,
        ]);

        // Contoh Soal (Contoh TKP - Pelayanan Publik)
        Question::create([
            'package_id' => $package->id,
            'category_id' => $tkp->id,
            'question_text' => 'Anda sedang melayani klien penting, tiba-tiba rekan kerja Anda meminta bantuan mendesak untuk tugas yang bukan tanggung jawab Anda. Sikap Anda adalah...',
            'opt_a' => 'Meninggalkan klien sejenak untuk membantu rekan.',
            'opt_b' => 'Meminta rekan menunggu sampai urusan dengan klien selesai.',
            'opt_c' => 'Menolak dengan tegas karena itu bukan tugas saya.',
            'opt_d' => 'Meminta rekan lain yang sedang luang untuk membantunya.',
            'opt_e' => 'Melayani klien sambil sesekali membantu rekan tersebut.',
            'correct_answer' => 'B', // Tidak terpakai di TKP, tapi tetap wajib diisi karena struktur database
            'discussion_text' => 'Pelayanan publik menuntut prioritas pada klien/masyarakat yang sedang dilayani. Menunda bantuan internal demi kepuasan klien eksternal adalah tindakan profesional.',
            'tkp_scores' => [
                'A' => 2,
                'B' => 5, // Jawaban paling tepat
                'C' => 1,
                'D' => 4,
                'E' => 3,
            ],
        ]);
    }
}