<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
    /**
     * HALAMAN KASIR
     */
    public function index()
    {
        $products = Product::where('is_active', 1)
            ->where('stok', '>', 0)
            ->get();

        return view('kasir.index', compact('products'));
    }

    /**
     * SIMPAN TRANSAKSI 
     */
  public function store(Request $request)
{
    $request->validate([
        'produk' => 'required|array|min:1',
        'bayar'  => 'required|numeric|min:0',
        'diskon_type' => 'nullable|in:percent,amount',
        'diskon_value' => 'nullable|numeric|min:0',
        'pajak_percent' => 'nullable|numeric|min:0'
    ]);

    DB::beginTransaction();

    try {

        $subtotal = 0;

        // =========================
        // HITUNG ITEM
        // =========================
        foreach ($request->produk as $item) {

            $harga = $item['harga'];
            $qty   = $item['qty'];

            $subtotalItem = $harga * $qty;
            $subtotal += $subtotalItem;
        }

        // =========================
        // DISKON TRANSAKSI
        // =========================
        $diskonTotal = 0;

        if ($request->diskon_type) {

            if ($request->diskon_type == 'percent') {
                $diskonTotal = $subtotal * ($request->diskon_value / 100);
            } else {
                $diskonTotal = $request->diskon_value;
            }

            if ($diskonTotal > $subtotal) {
                $diskonTotal = $subtotal;
            }
        }

        $dpp = $subtotal - $diskonTotal;

        // =========================
        // PAJAK
        // =========================
        $pajakPercent = $request->pajak_percent ?? 0;
        $pajakTotal = $dpp * ($pajakPercent / 100);

        $grandTotal = $dpp + $pajakTotal;

        // =========================
        // VALIDASI PEMBAYARAN
        // =========================
        if ($request->bayar < $grandTotal) {
            return back()->withErrors('Jumlah bayar kurang');
        }

        // =========================
        // SIMPAN HEADER
        // =========================
        $transaksiId = DB::table('transaksis')->insertGetId([
            'kode_transaksi' => 'TRX-' . date('YmdHis'),
            'subtotal'       => $subtotal,
            'discount'       => $diskonTotal,
            'tax_percent'    => $pajakPercent,
            'tax_amount'     => $pajakTotal,
            'total'          => $grandTotal,
            'bayar'          => $request->bayar,
            'kembalian'      => $request->bayar - $grandTotal,
            'user_id'        => auth()->id(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // =========================
        // SIMPAN DETAIL + UPDATE STOK
        // =========================
        foreach ($request->produk as $item) {

            DB::table('detail_transaksis')->insert([
                'transaksi_id' => $transaksiId,
                'product_id'   => $item['produk_id'],
                'nama_produk'  => $item['nama_produk'],
                'qty'          => $item['qty'],
                'harga'        => $item['harga'],
                'sub_total'    => $item['harga'] * $item['qty'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            Product::where('id', $item['produk_id'])
                ->decrement('stok', $item['qty']);
        }

        DB::commit();

        return redirect()
            ->route('kasir.index')
            ->with('success', 'Transaksi berhasil disimpan');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->withErrors($e->getMessage());
    }
}
    /**
     * MIDTRANS QRIS TOKEN
     */
    public function midtransToken(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:1',
        ]);

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = false; // ubah true kalau production
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => 'POS-' . time(),
                'gross_amount'=> (int) $request->total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Customer',
            ],
            'enabled_payments' => ['qris'],
        ];

        return response()->json([
            'token' => Snap::getSnapToken($params)
        ]);
    }
}
