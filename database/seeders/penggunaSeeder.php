<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penggunaList = [
            [
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'nomor_telepon' => '081234567890',
                'jabatan' => 'Staff IT',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'nomor_telepon' => '081234567891',
                'jabatan' => 'Manager HR',
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'nomor_telepon' => '081234567892',
                'jabatan' => 'Staff Marketing',
            ],
            [
                'nama' => 'Dewi Putri',
                'email' => 'dewi@example.com',
                'nomor_telepon' => '081234567893',
                'jabatan' => 'Staff Finance',
            ],
            [
                'nama' => 'Rudi Hermawan',
                'email' => 'rudi@example.com',
                'nomor_telepon' => '081234567894',
                'jabatan' => 'Staff Admin',
            ],
        ];

        foreach ($penggunaList as $pengguna) {
            Pengguna::create($pengguna);
        }
    }
}
