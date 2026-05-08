<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Ini dia "kabel" yang tadi sempat hilang, Boss!
    public function session()
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }
}