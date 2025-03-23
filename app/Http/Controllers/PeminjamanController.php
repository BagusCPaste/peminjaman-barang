<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])->get();
        return Inertia::render('Peminjaman', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function adminIndex()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])->get();
        return Inertia::render('Peminjaman', [
            'peminjaman' => $peminjaman,
            'isAdmin' => true
        ]);
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        $barang = Barang::where('status', 'Tersedia')->where('stok', '>', 0)->get();
        
        return Inertia::render('Peminjaman/Create', [
            'pengguna' => $pengguna,
            'barang' => $barang
        ]);
    }

    /**
     * Memastikan setiap User memiliki data Pengguna yang sesuai
     * @param \App\Models\User $user
     * @return \App\Models\Pengguna
     */
    private function getPenggunaFromUser($user)
    {
        if (!$user) {
            \Log::error('Tidak dapat mendapatkan pengguna: user null');
            return null;
        }
        
        \Log::info('Mencari pengguna untuk user:', [
            'user_id' => $user->id, 
            'name' => $user->name, 
            'email' => $user->email
        ]);
        
        // Coba dapatkan dari relasi
        if ($user->pengguna) {
            \Log::info('Pengguna ditemukan melalui relasi');
            return $user->pengguna;
        }
        
        // Coba cari berdasarkan email
        $pengguna = Pengguna::where('email', $user->email)->first();
        if ($pengguna) {
            \Log::info('Pengguna ditemukan berdasarkan email');
            return $pengguna;
        }
        
        // Buat pengguna baru jika tidak ditemukan
        \Log::info('Membuat data pengguna baru untuk user_id: ' . $user->id);
        $pengguna = Pengguna::create([
            'nama' => $user->name,
            'email' => $user->email,
            'nomor_telepon' => '',
            'jabatan' => $user->role ?? 'user'
        ]);
        
        \Log::info('Pengguna baru dibuat dengan ID: ' . $pengguna->id);
        return $pengguna;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'nullable|exists:pengguna,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Log untuk debugging
            \Log::info('Peminjaman baru:', $request->all());
            
            // Dapatkan pengguna_id
            $pengguna_id = null;
            $pengguna = null;
            
            // Jika admin memberikan pengguna_id, gunakan itu
            if ($request->pengguna_id) {
                $pengguna_id = $request->pengguna_id;
                $pengguna = Pengguna::find($pengguna_id);
                \Log::info('Menggunakan pengguna dari request admin:', [
                    'pengguna_id' => $pengguna_id,
                    'nama' => $pengguna ? $pengguna->nama : 'tidak ditemukan'
                ]);
            } 
            // Jika tidak, cari pengguna berdasarkan user yang login
            else {
                $user = auth()->user();
                if (!$user) {
                    \Log::error('User tidak terautentikasi');
                    return response()->json(['message' => 'User tidak terautentikasi'], 401);
                }
                
                $pengguna = $this->getPenggunaFromUser($user);
                if (!$pengguna) {
                    \Log::error('Gagal mendapatkan atau membuat pengguna untuk user_id: ' . $user->id);
                    return response()->json(['message' => 'Gagal mendapatkan data pengguna'], 500);
                }
                
                $pengguna_id = $pengguna->id;
                \Log::info('Menggunakan pengguna dari user yang login:', [
                    'pengguna_id' => $pengguna_id,
                    'nama' => $pengguna->nama
                ]);
            }
            
            // Validasi final
            if (!$pengguna_id) {
                \Log::error('pengguna_id masih null setelah semua pengecekan');
                return response()->json(['message' => 'Gagal mendapatkan ID pengguna yang valid'], 400);
            }

            // Buat record peminjaman
            $peminjaman = Peminjaman::create([
                'pengguna_id' => $pengguna_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'Dipinjam',
            ]);

            \Log::info('Peminjaman dibuat dengan ID: ' . $peminjaman->id . ', pengguna_id: ' . $peminjaman->pengguna_id);

            // Buat detail peminjaman
            foreach ($request->items as $item) {
                $barang = Barang::find($item['barang_id']);
                
                if (!$barang) {
                    throw new \Exception("Barang tidak ditemukan");
                }
                
                if ($barang->stok < $item['jumlah']) {
                    throw new \Exception("Stok barang {$barang->nama} tidak mencukupi. Tersedia: {$barang->stok}");
                }

                // Log untuk debugging
                \Log::info("Menambah barang ke peminjaman: {$barang->nama}, jumlah: {$item['jumlah']}");

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah']
                ]);

                // Update stok barang
                $barang->update([
                    'stok' => $barang->stok - $item['jumlah'],
                    'status' => $barang->stok - $item['jumlah'] > 0 ? 'Tersedia' : 'Dipinjam'
                ]);
            }

            DB::commit();
            
            // Muat peminjaman dengan relasinya
            $peminjaman->load('detailPeminjaman.barang', 'pengguna');
            
            // Verifikasi data yang akan dikembalikan ke client
            \Log::info('Data peminjaman yang akan dikirim ke client:', [
                'id' => $peminjaman->id,
                'pengguna_id' => $peminjaman->pengguna_id,
                'pengguna_nama' => $peminjaman->pengguna ? $peminjaman->pengguna->nama : 'NULL',
                'pengguna_loaded' => $peminjaman->relationLoaded('pengguna'),
                'detail_count' => $peminjaman->detailPeminjaman->count(),
            ]);
            
            return response()->json([
                'message' => 'Peminjaman berhasil dibuat',
                'data' => $peminjaman
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saat membuat peminjaman: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        return Inertia::render('Peminjaman/Show', [
            'peminjaman' => $peminjaman->load('detailPeminjaman.barang', 'pengguna')
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Dipinjam,Dikembalikan',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            if ($request->status === 'Dikembalikan' && $peminjaman->status === 'Dipinjam') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $barang = $detail->barang;
                    $barang->update([
                        'stok' => $barang->stok + $detail->jumlah,
                        'status' => 'Tersedia'
                    ]);
                }
            }

            $peminjaman->update($request->all());

            DB::commit();
            return redirect()->route('user.peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateStatus(Request $request, Peminjaman $peminjaman)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Dipinjam,Dikembalikan',
            'tanggal_kembali' => 'required_if:status,Dikembalikan|nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            if ($request->status === 'Dikembalikan' && $peminjaman->status === 'Dipinjam') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $barang = $detail->barang;
                    $barang->update([
                        'stok' => $barang->stok + $detail->jumlah,
                        'status' => 'Tersedia'
                    ]);
                }
                $peminjaman->update([
                    'status' => 'Dikembalikan',
                    'tanggal_kembali' => $request->tanggal_kembali ?? now()
                ]);
            }

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Status peminjaman berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        try {
            DB::beginTransaction();

            if ($peminjaman->status === 'Dipinjam') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $barang = $detail->barang;
                    $barang->update([
                        'stok' => $barang->stok + $detail->jumlah,
                        'status' => 'Tersedia'
                    ]);
                }
            }

            $peminjaman->delete();

            DB::commit();
            return redirect()->route('user.peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function adminBarangPeminjaman($barang_id)
    {
        // Ambil data peminjaman berdasarkan ID barang
        $peminjaman = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])
            ->whereHas('detailPeminjaman', function($query) use ($barang_id) {
                $query->where('barang_id', $barang_id);
            })
            ->get();
            
        $barang = Barang::findOrFail($barang_id);
        
        return Inertia::render('Peminjaman', [
            'peminjaman' => $peminjaman,
            'isAdmin' => true,
            'filter_barang' => $barang,
            'title' => 'Peminjam Barang: ' . $barang->nama
        ]);
    }

    /**
     * API method untuk mengambil semua data peminjaman
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        try {
            $peminjaman = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])->get();
            
            // Log untuk debugging
            \Log::info('API Index peminjaman dipanggil', [
                'count' => $peminjaman->count(),
                'first_item' => $peminjaman->first() ? [
                    'id' => $peminjaman->first()->id,
                    'pengguna_id' => $peminjaman->first()->pengguna_id,
                    'pengguna_nama' => $peminjaman->first()->pengguna ? $peminjaman->first()->pengguna->nama : null,
                ] : null
            ]);
            
            return response()->json($peminjaman);
        } catch (\Exception $e) {
            \Log::error('Error pada apiIndex peminjaman: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }

    /**
     * API method untuk mengambil data peminjaman berdasarkan user ID
     * @param int $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUserIndex($user_id)
    {
        try {
            // Cari pengguna yang terkait dengan user_id
            $user = \App\Models\User::find($user_id);
            if (!$user) {
                \Log::error('User tidak ditemukan dengan ID: ' . $user_id);
                return response()->json(['error' => 'User tidak ditemukan'], 404);
            }
            
            // Cari Pengguna terkait
            $pengguna = $this->getPenggunaFromUser($user);
            if (!$pengguna) {
                // Buat pengguna baru jika tidak ditemukan
                \Log::info('Membuat data pengguna baru untuk user_id: ' . $user_id);
                $pengguna = Pengguna::create([
                    'nama' => $user->name,
                    'email' => $user->email,
                    'nomor_telepon' => '',
                    'jabatan' => $user->role ?? 'user'
                ]);
                \Log::info('Pengguna baru dibuat dengan ID: ' . $pengguna->id);
            }
            
            $peminjaman = Peminjaman::with(['pengguna', 'detailPeminjaman.barang'])
                ->where('pengguna_id', $pengguna->id)
                ->get();
                
            \Log::info('API User Index peminjaman dipanggil', [
                'user_id' => $user_id,
                'pengguna_id' => $pengguna->id,
                'count' => $peminjaman->count()
            ]);
            
            return response()->json($peminjaman);
        } catch (\Exception $e) {
            \Log::error('Error pada apiUserIndex peminjaman: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }

    /**
     * API method untuk mengembalikan barang
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnItems(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with('detailPeminjaman.barang')->findOrFail($id);
            
            if ($peminjaman->status === 'Dikembalikan') {
                return response()->json(['message' => 'Barang sudah dikembalikan sebelumnya'], 400);
            }

            foreach ($peminjaman->detailPeminjaman as $detail) {
                $barang = $detail->barang;
                
                // Update stok barang
                $barang->update([
                    'stok' => $barang->stok + $detail->jumlah,
                    'status' => 'Tersedia'
                ]);
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'Dikembalikan',
                'tanggal_kembali' => now()
            ]);

            DB::commit();
            return response()->json(['message' => 'Barang berhasil dikembalikan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API method untuk menghapus peminjaman
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiDestroy($id)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with('detailPeminjaman.barang')->findOrFail($id);

            if ($peminjaman->status === 'Dipinjam') {
                foreach ($peminjaman->detailPeminjaman as $detail) {
                    $barang = $detail->barang;
                    // Update stok barang
                    $barang->update([
                        'stok' => $barang->stok + $detail->jumlah,
                        'status' => 'Tersedia'
                    ]);
                }
            }

            $peminjaman->detailPeminjaman()->delete();
            $peminjaman->delete();

            DB::commit();
            return response()->json(['message' => 'Peminjaman berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
} 