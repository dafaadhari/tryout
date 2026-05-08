<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Mengizinkan mass assignment selain ID

    // Relasi: Satu Kategori memiliki banyak Soal
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}