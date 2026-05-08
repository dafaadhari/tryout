<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamController;
use App\Models\Package;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// --- BAGIAN INI YANG KITA UBAH BOSS ---
Route::get('/dashboard', function () {
    // Ambil data paket yang aktif dari database
    $packages = \App\Models\Package::where('is_active', true)->get();

    // Tarik semua hasil ujian milik user yang sedang login
    $results = \App\Models\ExamResult::whereHas('session', function($query) {
        $query->where('user_id', auth()->id());
    })->get();

    // Kalkulasi Statistik
    $totalFinished = $results->count();
    $averageScore = $totalFinished > 0 ? number_format($results->avg('total_score'), 2) : 0;
    
    // Cek kelulusan dari ujian terakhir yang dikerjakan
    $lastResult = $results->last();
    $passStatus = $lastResult ? ($lastResult->is_passed ? 'LULUS' : 'TIDAK LULUS') : '-';
    return Inertia::render('Dashboard', [
        'packages' => $packages, // Kirim datanya ke React
        'stats' => [
            'totalFinished' => $totalFinished,
            'averageScore' => $averageScore,
            'passStatus' => $passStatus
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');
// --------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/exam/{id}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/exam/{sessionId}/autosave', [ExamController::class, 'autosave'])->name('exam.autosave');
    Route::post('/exam/{sessionId}/submit', [ExamController::class, 'submit'])->name('exam.submit');
});

require __DIR__.'/auth.php';