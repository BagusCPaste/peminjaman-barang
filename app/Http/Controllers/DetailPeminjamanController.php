<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailPeminjamanController extends Controller
{
    public function index()
    {
        $detailPeminjaman = DetailPeminjaman::with(['peminjaman', 'barang'])->get();
        return response()->json(['data' => $detailPeminjaman]);
    }

    public function show(DetailPeminjaman $detailPeminjaman)
    {
        return response()->json([
            'data' => $detailPeminjaman->load(['peminjaman', 'barang'])
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detailPeminjaman = DetailPeminjaman::create($request->all());
        return response()->json(['data' => $detailPeminjaman->load(['peminjaman', 'barang'])], 201);
    }

    public function update(Request $request, DetailPeminjaman $detailPeminjaman)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detailPeminjaman->update($request->all());
        return response()->json(['data' => $detailPeminjaman->load(['peminjaman', 'barang'])]);
    }

    public function destroy(DetailPeminjaman $detailPeminjaman)
    {
        $detailPeminjaman->delete();
        return response()->json(null, 204);
    }
} 