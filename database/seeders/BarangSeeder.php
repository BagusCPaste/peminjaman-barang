<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangList = [
            [
                'nama' => 'Laptop Asus',
                'kode' => 'LAP001',
                'kategori' => 'Elektronik',
                'stok' => 5,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Proyektor Epson',
                'kode' => 'PRO001',
                'kategori' => 'Elektronik',
                'stok' => 3,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Kamera Canon',
                'kode' => 'KAM001',
                'kategori' => 'Elektronik',
                'stok' => 2,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Meja Meeting',
                'kode' => 'MEJ001',
                'kategori' => 'Furniture',
                'stok' => 10,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Kursi Kantor',
                'kode' => 'KUR001',
                'kategori' => 'Furniture',
                'stok' => 20,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Printer HP',
                'kode' => 'PRI001',
                'kategori' => 'Elektronik',
                'stok' => 4,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Whiteboard',
                'kode' => 'WHI001',
                'kategori' => 'Perlengkapan',
                'stok' => 6,
                'status' => 'Tersedia',
            ],
            [
                'nama' => 'Sound System',
                'kode' => 'SOU001',
                'kategori' => 'Elektronik',
                'stok' => 2,
                'status' => 'Tersedia',
            ],
        ];

        foreach ($barangList as $barang) {
            Barang::create($barang);
        }
    }
}
