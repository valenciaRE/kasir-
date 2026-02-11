<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBarang;
use App\Models\Product;
use App\Models\ItemPengeluaranBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengeluaranBarangController extends Controller
{
    public function index()
    {
        return view('pengeluaran-barang.index');
    }

    public function cekharga(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->id);

        return response()->json([
            'harga_jual' => $product->harga_jual,
            'stok'       => $product->stok
        ]);
    }

    public function store(Request $request)
    {
        if (empty($request->produk)) {
            toast()->error('Tidak ada produk yang dipilih');
            return redirect()->back();
        }

        $request->validate([
            'produk' => 'required|array|min:1',
            'bayar'  => 'required|numeric|min:1',
        ]);

        $produk = collect($request->produk);
        $bayar  = (int) $request->bayar;
        $total  = (int) $produk->sum('sub_total');
        $kembalian = $bayar - $total;

        if ($bayar < $total) {
            toast()->error('Jumlah bayar tidak mencukupi');
            return redirect()->back()->withInput();
        }

        // =========================
        // SIMPAN HEADER
        // =========================
        $pengeluaran = PengeluaranBarang::create([
            'nomor_pengeluaran' => PengeluaranBarang::nomorPengeluaran(),
            'nama_petugas'      => Auth::user()->name,
            'bayar'             => $bayar,
            'kembalian'         => $kembalian,
            'total_harga'       => $total,
        ]);

        // =========================
        // SIMPAN DETAIL
        // =========================
        foreach ($produk as $item) {

            $product = Product::findOrFail($item['produk_id']);

            ItemPengeluaranBarang::create([
                'nomor_pengeluaran' => $pengeluaran->nomor_pengeluaran,
                'product_id'        => $product->id,
                'nama_produk'       => $item['nama_produk'], // ✅ FIX UTAMA
                'qty'               => $item['qty'],
                'harga'             => $item['harga'],
                'sub_total'         => $item['sub_total'],
            ]);

            // kurangi stok
            $product->decrement('stok', $item['qty']);
        }

        toast()->success('Transaksi berhasil disimpan');
        // redirect ke halaman print struk
        return redirect()->route('pengeluaran-barang.print', $pengeluaran->id);
       
    }

    public function laporan()
    {
        $data = PengeluaranBarang::orderBy('created_at', 'desc')->get()->map(function($item){
            $item->tanggal_transaksi = Carbon::parse($item->created_at)->locale('id')->translatedFormat('l,d F Y');
            return $item;
        });

        return view('pengeluaran-barang.laporan', compact('data'));
    }

    public function detailLaporan(string $nomorpengeluaran) {
        $data = PengeluaranBarang::with('items')->where('nomor_pengeluaran', $nomorpengeluaran)->first();
        $data->total_harga = $data->items->sum('sub_total');
        $data->tanggal_transaksi = carbon::parse($data->created_at)->locale('id')->translatedFormat('l,d F Y');
        return view('pengeluaran-barang.detail', compact('data'));
    }

   public function print($id)
{
    $pengeluaran = PengeluaranBarang::with('items.product')
        ->findOrFail($id);

    return view('pengeluaran-barang.print', compact('pengeluaran'));
}

    
}
