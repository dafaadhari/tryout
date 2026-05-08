import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

// Tambahkan 'packages' di dalam parameter props untuk menangkap data dari Controller/web.php
export default function Dashboard({ auth, packages, stats }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-slate-800 leading-tight">Dashboard Peserta</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12 bg-slate-50 min-h-screen">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    
                    {/* Welcome Card - Glassmorphism Effect */}
                    <div className="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                        <div className="relative z-10">
                            <h3 className="text-3xl font-bold mb-2">Selamat datang, {auth.user.name}! 👋</h3>
                            <p className="text-blue-100 text-lg">Siap untuk menaklukkan ujian CPNS tahun ini? Mari mulai berlatih.</p>
                        </div>
                        <div className="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                        <div className="absolute -bottom-24 -right-12 w-48 h-48 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>
                    </div>

                    {/* Quick Stats Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
                            <div className="bg-blue-100 p-3 rounded-xl text-blue-600">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p className="text-slate-500 text-sm font-medium">Total Try Out Selesai</p>
                                <p className="text-2xl font-bold text-slate-800">{stats.totalFinished} <span className="text-sm font-normal text-slate-400">Paket</span></p>
                            </div>
                        </div>

                        <div className="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
                            <div className="bg-green-100 p-3 rounded-xl text-green-600">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <div>
                                <p className="text-slate-500 text-sm font-medium">Rata-rata Skor</p>
                                <p className="text-2xl font-bold text-slate-800">{stats.averageScore}</p>
                            </div>
                        </div>

                        <div className="bg-white/80 backdrop-blur-md rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
                            <div className="bg-red-100 p-3 rounded-xl text-red-600">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p className="text-slate-500 text-sm font-medium">Status Kelulusan</p>
                                <p className={`text-2xl font-bold ${stats.passStatus === 'LULUS' ? 'text-green-600' : stats.passStatus === 'TIDAK LULUS' ? 'text-red-600' : 'text-slate-800'}`}>
                                {stats.passStatus}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Section Daftar Paket Ujian */}
                    <div className="mt-8">
                        <h3 className="text-xl font-bold text-slate-800 mb-4">Paket Try Out Tersedia</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            {/* Menampilkan data dinamis dari Database */}
                            {packages && packages.length > 0 ? (
                                packages.map((pkg) => (
                                    <div key={pkg.id} className="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                        <div className="flex justify-between items-start mb-4">
                                            <span className="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Premium</span>
                                            <span className="text-slate-500 text-sm flex items-center">
                                                <svg className="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {pkg.duration} Menit
                                            </span>
                                        </div>
                                        <h4 className="text-lg font-bold text-slate-800 mb-2">{pkg.title}</h4>
                                        <p className="text-slate-500 text-sm mb-6">Simulasi CAT CPNS lengkap dengan 110 soal berstandar BKN (TWK, TIU, TKP).</p>
                                        
                                        <Link href={`/exam/${pkg.id}`} className="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200">
                                            Mulai Ujian
                                        </Link>
                                    </div>
                                ))
                            ) : (
                                <p className="text-slate-500 italic">Belum ada paket Try Out yang tersedia saat ini.</p>
                            )}

                        </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}