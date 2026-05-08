<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Satu Paket memiliki banyak Soal
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}