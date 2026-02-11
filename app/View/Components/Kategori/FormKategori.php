<?php

namespace App\View\Components\Kategori;

use App\Models\Kategori; // ✅ WAJIB
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormKategori extends Component
{
    public $id;
    public $nama_kategori;
    public $deskripsi;

    /**
     * Create a new component instance.
     */
    public function __construct($id = null)
    {
        if ($id) {
            $kategori = Kategori::find($id);

            if ($kategori) { // ✅ CEK NULL
                $this->id = $kategori->id;
                $this->nama_kategori = $kategori->nama_kategori;
                $this->deskripsi = $kategori->deskripsi;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.kategori.form-kategori');
    }
}
