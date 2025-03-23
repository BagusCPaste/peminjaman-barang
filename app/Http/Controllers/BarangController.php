<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return Inertia::render('Barang', [
            'barang' => $barang,
            'isAdmin' => true
        ]);
    }

    public function userIndex()
    {
        $barang = Barang::where('status', 'Tersedia')->where('stok', '>', 0)->get();
        return Inertia::render('Barang', [
            'barang' => $barang,
            'isUser' => true
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:barang,kode',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipinjam'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $barang = Barang::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'kategori' => $request->kategori,
                'stok' => $request->stok,
                'status' => $request->status
            ]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Barang berhasil ditambahkan',
                    'barang' => [
                        'id' => $barang->id,
                        'kode' => $barang->kode,
                        'nama' => $barang->nama,
                        'kategori' => $barang->kategori,
                        'stok' => $barang->stok,
                        'status' => $barang->status
                    ]
                ]);
            }
            
            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan barang: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('barang.index')->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang)
    {
        return Inertia::render('Barang/Show', [
            'barang' => $barang
        ]);
    }

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:barang,kode,' . $barang->id,
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipinjam'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $barang->update([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'kategori' => $request->kategori,
                'stok' => $request->stok,
                'status' => $request->status
            ]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Barang berhasil diperbarui',
                    'barang' => [
                        'id' => $barang->id,
                        'kode' => $barang->kode,
                        'nama' => $barang->nama,
                        'kategori' => $barang->kategori,
                        'stok' => $barang->stok,
                        'status' => $barang->status
                    ]
                ]);
            }
            
            return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui barang: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('barang.index')->with('error', 'Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
        $barang->delete();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Barang berhasil dihapus'
                ]);
            }
            
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus barang'
                ], 500);
            }
            
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang');
        }
    }

    /**
     * API method untuk mengambil semua data barang
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        // Log untuk debugging
        \Log::info('API Barang: Mengambil semua data barang');
        
        $barang = Barang::all();
        
        // Pastikan response berupa array
        $response = $barang->toArray();
        
        // Log untuk debugging
        \Log::info('API Barang: Mengembalikan ' . count($response) . ' barang');
        
        return response()->json($response);
    }
    
    /**
     * API method untuk mengambil data barang tersedia untuk user
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUserIndex()
    {
        // Log untuk debugging
        \Log::info('API Barang User: Mengambil data barang tersedia');
        
        $barang = Barang::where('status', 'Tersedia')
                        ->where('stok', '>', 0)
                        ->get();
        
        // Pastikan response berupa array
        $response = $barang->toArray();
        
        // Log untuk debugging
        \Log::info('API Barang User: Mengembalikan ' . count($response) . ' barang tersedia');
        
        return response()->json($response);
    }

    public function createDummyData()
    {
        try {
            \Log::info('Creating dummy data');
            
            // Cek apakah sudah ada data
            $existingCount = Barang::count();
            
            if ($existingCount > 0) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Data sudah ada, tidak perlu menambahkan dummy data',
                    'count' => $existingCount
                ]);
            }
            
            // Buat dummy data
            $dummyData = [
                [
                    'nama' => 'Laptop Dell XPS 13',
                    'kode' => 'BRG-001',
                    'kategori' => 'Elektronik',
                    'stok' => 5,
                    'status' => 'Tersedia'
                ],
                [
                    'nama' => 'Proyektor Epson EB-E01',
                    'kode' => 'BRG-002',
                    'kategori' => 'Elektronik',
                    'stok' => 3,
                    'status' => 'Tersedia'
                ],
                [
                    'nama' => 'Meja Kantor',
                    'kode' => 'BRG-003',
                    'kategori' => 'Furniture',
                    'stok' => 10,
                    'status' => 'Tersedia'
                ],
                [
                    'nama' => 'Kursi Ergonomis',
                    'kode' => 'BRG-004',
                    'kategori' => 'Furniture',
                    'stok' => 0,
                    'status' => 'Dipinjam'
                ]
            ];
            
            foreach ($dummyData as $data) {
                Barang::create($data);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Dummy data berhasil dibuat',
                'count' => count($dummyData)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating dummy data', ['error' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat dummy data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function debug()
    {
        try {
            $barang = Barang::all();
            
            $data = [
                'count' => $barang->count(),
                'items' => $barang->map(function($item) {
                    $attributes = $item->getAttributes();
                    return [
                        'id' => $item->id,
                        'raw' => $item->toArray(),
                        'nama' => $item->nama,
                        'kode' => $item->kode,
                        'properties' => [
                            'direct_field_access' => [
                                'nama' => $item->nama,
                                'kode' => $item->kode,
                            ],
                            'compat_accessors' => [
                                'nama_barang' => $item->nama_barang,
                                'kode_barang' => $item->kode_barang
                            ],
                            'attributes' => $attributes,
                            'column_exists' => [
                                'nama_column_exists' => array_key_exists('nama', $attributes),
                                'kode_column_exists' => array_key_exists('kode', $attributes),
                                'nama_barang_column_exists' => array_key_exists('nama_barang', $attributes),
                                'kode_barang_column_exists' => array_key_exists('kode_barang', $attributes),
                            ]
                        ]
                    ];
                })
            ];
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
} 