<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class PenerimaanBarang extends Model
{
    use HasFactory;

    // Menggunakan guarded id artinya semua field lain boleh diisi (Mass Assignment)
    protected $guarded = ['id'];

    /**
     * BOOTED METHOD
     * Ini akan otomatis mengisi 'petugas_penerimaan' dengan nama user yang login
     * saat data sedang dibuat (creating).
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                // Pastikan nama kolom ini SAMA dengan yang ada di database (petugas_penerimaan)
                $model->petugas_penerimaan = Auth::user()->name;
            }
        });
    }

    /**
     * RELASI KE USER
     * Jika di tabel database kolomnya bernama 'petugas_penerimaan' dan berisi Nama,
     * relasi ini mungkin perlu disesuaikan jika Anda ingin menghubungkan ID.
     * Tapi jika hanya ingin menyimpan string nama, fungsi booted di atas sudah cukup.
     */
    public function user()
    {
        // Jika petugas_penerimaan menyimpan NAMA, relasi belongsTo biasanya butuh ID.
        // Jika Anda punya kolom user_id di tabel, gunakan ini:
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function nomorPenerimaan()
    {
        // Menggunakan count atau max id untuk generate nomor unik
        $max = self::max('id') ?? 0;
        $prefix = 'PBR-';
        $date = date('dmy');
        $nomor = $prefix . $date . str_pad($max + 1, 4, '0', STR_PAD_LEFT);
        return $nomor;
    }

    public function items()
    {
        return $this->hasMany(ItemPenerimaanBarang::class, 'nomor_penerimaan', 'nomor_penerimaan');
    }
}