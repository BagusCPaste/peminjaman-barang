<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    
    protected $fillable = [
        'nama',
        'email',
        'nomor_telepon',
        'jabatan'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nomor_telepon' => 'string',
    ];
    
    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($pengguna) {
            // Pastikan nomor_telepon tidak null
            if (!isset($pengguna->nomor_telepon)) {
                $pengguna->nomor_telepon = '';
            }
            
            // Pastikan jabatan tidak null
            if (!isset($pengguna->jabatan)) {
                $pengguna->jabatan = 'user';
            }
        });
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
    
    /**
     * Get the user associated with this pengguna.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
} 