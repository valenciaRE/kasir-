<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('kategori')->get();
        confirmDelete('Hapus Data', 'Apakah Anda yakin ingin menghapus data ini?');

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('product.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $id = $request->input('id');

        $request->validate([
            'name_product'       => 'required|unique:products,name_product,' . $id,
            'harga_jual'         => 'required|numeric|min:0',
            'harga_beli_pokok'   => 'required|numeric|min:0',
            'kategori_id'        => 'nullable|exists:kategoris,id',
            'stok'               => 'required|integer|min:0',
            'stok_minimal'       => 'required|integer|min:0',
        ]);

        $newRequest = [
            'name_product'      => $request->name_product,
            'harga_jual'        => $request->harga_jual,
            'harga_beli_pokok'  => $request->harga_beli_pokok,
            'kategori_id'       => $request->kategori_id,
            'stok'              => $request->stok,
            'stok_minimal'      => $request->stok_minimal,
            'is_active'         => $request->is_active ? true : false,
        ];

        if (!$id) {
            $newRequest['sku'] = Product::nomorSku();
        }

        Product::updateOrCreate(
            ['id' => $id],
            $newRequest
        );

        toast()->success('Data berhasil disimpan');
        return redirect()->route('master-data.product.index');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            toast()->success('Data berhasil dihapus');
        } catch (\Exception $e) {
            toast()->error('Gagal menghapus data');
        }

        return redirect()->route('master-data.product.index');
    }

    public function getData()
    {
        $search = request()->query('search');

        $query = Product::select('id', 'name_product as nama_produk');
        $product = $query
            ->where('name_product', 'like', '%' . $search . '%')
            ->get();

        return response()->json($product);
    }

    public function cekStok()
    {
        $id = request()->query('id');
        $product = Product::find($id);
        $stok = $product ? $product->stok : 0;
        return response()->json($stok);
    }

    public function cekHarga()
    {
    $id = request()->query('id');
    $product = Product::find($id);
    $harga = $product ? $product->harga_jual : 0;
    return response()->json($harga);
    }
}