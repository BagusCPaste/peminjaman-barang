<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'stok',
        'status'
    ];
    
    /**
     * Untuk kompatibilitas ke belakang dengan kode yang menggunakan nama_barang
     */
    public function getNamaBarangAttribute()
    {
        return $this->nama;
    }
    
    /**
     * Untuk kompatibilitas ke belakang dengan kode yang menggunakan kode_barang
     */
    public function getKodeBarangAttribute()
    {
        return $this->kode;
    }
    
    /**
     * Tambahkan appends untuk menyertakan properti ini saat serializasi
     * Untuk kompatibilitas dengan kode lama
     */
    protected $appends = ['nama_barang', 'kode_barang'];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
} 