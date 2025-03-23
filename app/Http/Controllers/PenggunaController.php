<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::all();
        return Inertia::render('Pengguna', [
            'pengguna' => $pengguna
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna',
            'nomor_telepon' => 'required|string|max:20',
            'jabatan' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Pengguna::create($request->all());
        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil disimpan');
    }

    public function show(Pengguna $pengguna)
    {
        return Inertia::render('Pengguna/Show', [
            'pengguna' => $pengguna
        ]);
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
            'nomor_telepon' => 'required|string|max:20',
            'jabatan' => 'nullable|string|max:255'
        ]);

        $pengguna->update($validatedData);
        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil dihapus');
    }

    /**
     * API method untuk mengambil semua data pengguna
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $pengguna = Pengguna::all();
        return response()->json($pengguna);
    }
} 