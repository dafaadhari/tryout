<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Cast tkp_scores dari format JSON di database menjadi Array di PHP
    protected $casts = [
        'tkp_scores' => 'array',
    ];

    // Relasi: Setiap soal dimiliki oleh satu Paket
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi: Setiap soal masuk ke dalam satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}