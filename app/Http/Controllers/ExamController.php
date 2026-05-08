<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ExamSession;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    // 1. Tampilkan Soal & Buat Sesi
    public function show($id)
    {
        $package = Package::with('questions.category')->findOrFail($id);

        if ($package->questions->count() == 0) {
            return redirect()->route('dashboard')->with('error', 'Paket soal belum tersedia.');
        }

        $session = ExamSession::firstOrCreate(
            ['user_id' => auth()->id(), 'package_id' => $package->id, 'status' => 'ongoing'],
            ['start_time' => now()]
        );

        $existingAnswers = ExamAnswer::where('exam_session_id', $session->id)
            ->pluck('answer', 'question_id')
            ->toArray();

        return Inertia::render('Exam/Show', [
            'package' => $package,
            'examSession' => $session,
            'existingAnswers' => (object) $existingAnswers
        ]);
    }

    // 2. Autosave Jawaban
    public function autosave(Request $request, $sessionId)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:A,B,C,D,E'
        ]);

        $session = ExamSession::where('id', $sessionId)->where('user_id', auth()->id())->firstOrFail();
        $question = Question::with('category')->find($request->question_id);
        
        $score = 0;
        if ($question->category->name === 'TKP') {
            $score = $question->tkp_scores[$request->answer] ?? 0;
        } else {
            $score = ($request->answer === $question->correct_answer) ? 5 : 0;
        }

        ExamAnswer::updateOrCreate(
            ['exam_session_id' => $session->id, 'question_id' => $question->id],
            ['answer' => $request->answer, 'score' => $score]
        );

        return response()->json(['status' => 'success']);
    }

    // 3. Submit Ujian & Hitung Nilai
    public function submit(Request $request, $sessionId)
    {
        $session = ExamSession::where('id', $sessionId)->where('user_id', auth()->id())->firstOrFail();

        if ($session->status === 'finished') {
            return redirect()->route('dashboard');
        }

        $session->update(['end_time' => now(), 'status' => 'finished']);

        $answers = ExamAnswer::with('question.category')->where('exam_session_id', $session->id)->get();

        $twk = 0; $tiu = 0; $tkp = 0;

        foreach ($answers as $ans) {
            if ($ans->question->category->name == 'TWK') {
                $twk += $ans->score;
            } elseif ($ans->question->category->name == 'TIU') {
                $tiu += $ans->score;
            } elseif ($ans->question->category->name == 'TKP') {
                $tkp += $ans->score;
            }
        }

        $isPassed = ($twk >= 65 && $tiu >= 80 && $tkp >= 166);

        ExamResult::create([
            'exam_session_id' => $session->id,
            'twk_score' => $twk,
            'tiu_score' => $tiu,
            'tkp_score' => $tkp,
            'total_score' => ($twk + $tiu + $tkp),
            'is_passed' => $isPassed,
        ]);

        return redirect()->route('dashboard');
    }
}