<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengguna;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_barang' => Barang::count(),
            'total_pengguna' => Pengguna::count(),
            'total_peminjaman_aktif' => Peminjaman::where('status', 'Dipinjam')->count(),
            'peminjaman_user' => []
        ];

        if (auth()->check() && auth()->user()->role !== 'admin') {
            $user = auth()->user();
            $pengguna = Pengguna::where('email', $user->email)->first();
            
            if ($pengguna) {
                $data['peminjaman_user'] = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])
                    ->where('pengguna_id', $pengguna->id)
                    ->latest()
                    ->get();
            }
        }

        return Inertia::render('Dashboard', $data);
    }
    
    /**
     * API endpoint untuk mendapatkan data dashboard
     */
    public function api()
    {
        try {
            $data = [
                'total_barang' => Barang::count(),
                'total_pengguna' => Pengguna::count(),
                'total_peminjaman_aktif' => Peminjaman::where('status', 'Dipinjam')->count(),
                'peminjaman_user' => []
            ];
            
            // Jika pengguna bukan admin, dapatkan data peminjaman mereka
            if (auth()->check() && auth()->user()->role !== 'admin') {
                $user = auth()->user();
                
                // Cari pengguna berdasarkan email
                $pengguna = Pengguna::where('email', $user->email)->first();
                
                if ($pengguna) {
                    $data['peminjaman_user'] = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])
                        ->where('pengguna_id', $pengguna->id)
                        ->latest()
                        ->get();
                }
            }
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            \Log::error('Error saat mengambil data dashboard: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }
} 