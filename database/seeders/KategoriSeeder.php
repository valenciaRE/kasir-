<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Pakaian Pria'],
            ['nama' => 'Pakaian Wanita'],
            ['nama' => 'Sepatu Pria'],
            ['nama' => 'Sepatu Wanita'],
            ['nama' => 'Tas Wanita'],
        ];

        foreach ($data as $item) {
            Kategori::create([
                'nama_kategori' => $item['nama'],
                'slug'          => Str::slug($item['nama']),
                'deskripsi'     => 'Lorem ipsum dolor sit amet consectetur adipisicing.',
            ]);
        }
    }
}