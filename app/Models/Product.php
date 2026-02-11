<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kategori_id',
        'name_product', // ✅ HARUS INI (SESUAI DATABASE)
        'sku',
        'harga_jual',
        'harga_beli_pokok',
        'stok',
        'stok_minimal',
        'is_active',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public static function nomorSku()
    {
        // SKU-0001
        $prefix = 'SKU-';
        $maxId = self::max('id');
        $sku = $prefix . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        return $sku;
    }
}
