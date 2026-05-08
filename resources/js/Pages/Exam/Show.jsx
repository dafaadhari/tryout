import React, { useState, useEffect } from 'react';
import { Head, router } from '@inertiajs/react';
import axios from 'axios';

export default function Show({ package: examPackage, examSession, existingAnswers }) {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState(existingAnswers || {});
    const [timeLeft, setTimeLeft] = useState(examPackage.duration * 60);

    const questions = examPackage.questions;
    const currentQuestion = questions[currentQuestionIndex];

    useEffect(() => {
        if (timeLeft <= 0) {
            handleFinishExam();
            return;
        }
        const timer = setInterval(() => setTimeLeft((prev) => prev - 1), 1000);
        return () => clearInterval(timer);
    }, [timeLeft]);

    const formatTime = (seconds) => {
        const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${h}:${m}:${s}`;
    };

    // Fungsi Autosave dipanggil setiap klik jawaban
    const handleAnswerChange = async (questionId, option) => {
        setAnswers({ ...answers, [questionId]: option });
        try {
            await axios.post(`/exam/${examSession.id}/autosave`, {
                question_id: questionId,
                answer: option
            });
        } catch (error) {
            console.error("Gagal autosave:", error);
        }
    };

    // Fungsi Tombol Selesai Ujian
    const handleFinishExam = () => {
        if (confirm("Yakin ingin menyelesaikan ujian? Anda tidak bisa mengubah jawaban lagi setelah ini.")) {
            router.post(`/exam/${examSession.id}/submit`);
        }
    };

    return (
        <div className="min-h-screen bg-slate-100 flex flex-col font-sans">
            <Head title={`Ujian: ${examPackage.title}`} />

            <header className="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div className="flex items-center space-x-4">
                        <div className="bg-blue-600 text-white font-bold px-3 py-1 rounded-lg">CAT</div>
                        <h1 className="font-semibold text-slate-800 truncate max-w-md">{examPackage.title}</h1>
                    </div>
                    <div className="flex items-center space-x-6">
                        <div className={`text-xl font-mono font-bold px-4 py-2 rounded-lg border bg-slate-100 text-slate-700 border-slate-200`}>
                            ⏱ {formatTime(timeLeft)}
                        </div>
                        <button onClick={handleFinishExam} className="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-medium transition">
                            Selesai Ujian
                        </button>
                    </div>
                </div>
            </header>

            <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col lg:flex-row gap-6">
                <div className="lg:w-3/4 flex flex-col gap-4">
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 flex-1">
                        <div className="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                            <span className="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Soal Ke-{currentQuestionIndex + 1}</span>
                        </div>
                        <p className="text-lg text-slate-800 mb-8 leading-relaxed">
                            {currentQuestion.question_text}
                        </p>
                        <div className="space-y-3">
                            {['A', 'B', 'C', 'D', 'E'].map((opt) => {
                                const optKey = `opt_${opt.toLowerCase()}`;
                                const isSelected = answers[currentQuestion.id] === opt;
                                return (
                                    <label key={opt} className={`flex items-start p-4 rounded-xl border cursor-pointer transition-all ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-slate-200'}`}>
                                        <input 
                                            type="radio" 
                                            name={`question_${currentQuestion.id}`} 
                                            value={opt}
                                            checked={isSelected}
                                            onChange={() => handleAnswerChange(currentQuestion.id, opt)}
                                            className="mt-1 w-5 h-5 text-blue-600"
                                        />
                                        <span className="ml-4 text-slate-700"><span className="font-bold mr-2">{opt}.</span> {currentQuestion[optKey]}</span>
                                    </label>
                                );
                            })}
                        </div>
                    </div>
                    <div className="flex justify-between items-center">
                        <button onClick={() => setCurrentQuestionIndex(prev => Math.max(0, prev - 1))} disabled={currentQuestionIndex === 0} className="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 disabled:opacity-50 transition">&larr; Sebelumnya</button>
                        <button onClick={() => setCurrentQuestionIndex(prev => Math.min(questions.length - 1, prev + 1))} disabled={currentQuestionIndex === questions.length - 1} className="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 disabled:opacity-50 transition">Selanjutnya &rarr;</button>
                    </div>
                </div>

                <div className="lg:w-1/4">
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
                        <h3 className="font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Navigasi Soal</h3>
                        <div className="grid grid-cols-5 gap-2">
                            {questions.map((q, index) => {
                                const isAnswered = answers[q.id] !== undefined;
                                const isCurrent = currentQuestionIndex === index;
                                return (
                                    <button 
                                        key={q.id} onClick={() => setCurrentQuestionIndex(index)}
                                        className={`h-10 w-full rounded-lg text-sm font-medium transition-all ${isCurrent ? 'ring-2 ring-blue-600 ' : ''} ${isAnswered ? 'bg-green-500 text-white' : 'bg-white border border-slate-300 text-slate-600'}`}
                                    >
                                        {index + 1}
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    );
}