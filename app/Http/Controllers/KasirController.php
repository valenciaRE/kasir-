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
     * SIMPAN TRANSAKSI (CASH)
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|array|min:1',
            'total'  => 'required|numeric|min:1',
            'bayar'  => 'required|numeric|min:1',
        ]);

        if ($request->bayar < $request->total) {
            return back()->withErrors('Jumlah bayar kurang');
        }

        DB::beginTransaction();
        try {

            // =========================
            // SIMPAN HEADER TRANSAKSI
            // =========================
            $transaksiId = DB::table('transaksis')->insertGetId([
                'kode_transaksi' => 'TRX-' . date('YmdHis'),
                'total'          => $request->total,
                'bayar'          => $request->bayar,
                'kembalian'      => $request->bayar - $request->total,
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
                    'sub_total'    => $item['sub_total'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // kurangi stok
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
