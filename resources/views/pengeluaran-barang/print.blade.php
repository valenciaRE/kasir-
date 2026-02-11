<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>
    <style>
@page {
    size: 58mm auto;
    margin: 0;
}

html, body {
    width: 58mm;
    margin: 0;
    padding: 0;
    font-family: monospace;
    font-size: 11px;
}

.struk {
    width: 58mm;
    padding: 4px;
}

.center {
    text-align: center;
}

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 4px 0;
}

@media print {
    html, body {
        width: 58mm;
    }
}
</style>
</head>

<body onload="window.print()">

<div class="struk">

    <div class="center">
        <b>PT Nusa Indo</b><br>
        Jl. Rungkut Asri Timur XV H No.2, V No.2 Blok H, Rungkut Kidul<br>
        ======================
    </div>

    <p>
        No : {{ $pengeluaran->nomor_pengeluaran }}<br>
        Tgl: {{ $pengeluaran->created_at->format('d/m/Y H:i') }}<br>
        Kasir: {{ $pengeluaran->nama_petugas }}
    </p>

    <hr>

    <table>
        @foreach($pengeluaran->items as $item)
        <tr>
            <td colspan="3">{{ $item->nama_produk }}</td>
        </tr>
        <tr>
            <td>{{ $item->jumlah }} x {{ number_format($item->harga_jual) }}</td>
            <td></td>
            <td align="right">{{ number_format($item->sub_total) }}</td>
        </tr>
        @endforeach
    </table>

    <hr>

    <table>
        <tr>
            <td>Total</td>
            <td align="right">{{ number_format($pengeluaran->total_harga) }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td align="right">{{ number_format($pengeluaran->bayar) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td align="right">{{ number_format($pengeluaran->kembalian) }}</td>
        </tr>
    </table>

    <div class="center">
        <br>* TERIMA KASIH *<br>
        Barang yang sudah dibeli<br>
        tidak dapat dikembalikan
    </div>

</div>

</body>
</html>