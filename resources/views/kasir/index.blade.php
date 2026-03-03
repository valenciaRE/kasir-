@extends('layouts.kasir')

@section('content')
<style>
    .product-column {
        height: 85vh;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    /* Area Struk disembunyikan di layar kasir */
    #receipt-section { display: none; }

    @media print {
        body * { visibility: hidden; }
        nav, .sidebar, .container-fluid, button, input, select { display: none !important; }
        #receipt-section, #receipt-section * { 
            visibility: visible; 
            display: block !important; 
        }
        #receipt-section { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 80mm; 
            /* Menggunakan font yang lebih tebal dan hitam pekat */
            font-family: 'Arial', sans-serif; 
            padding: 5px;
            color: #000 !important; /* Memastikan warna hitam pekat */
            background-color: #fff;
        }
        
        /* Membuat teks lebih tegas */
        #receipt-section h4 { font-weight: 800; margin-bottom: 5px; }
        #receipt-section table { font-weight: 600; } 
        
        /* Mengganti garis putus-putus menjadi lebih gelap */
        hr { 
            border-top: 1px solid #000 !important; 
            margin: 5px 0;
            opacity: 1;
        }
        
        @page { margin: 0; }
    }
</style>

<div class="container-fluid">
<div class="row">

    {{-- ================= AREA PRODUK ================= --}}
    <div class="col-lg-8 col-md-7 product-column">
        <h5 class="mb-3 fw-bold">Kasir POS</h5>
        <div class="row">
            @forelse($products as $p)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                <div class="card produk-card shadow-sm h-100"
                     style="cursor:pointer"
                     data-id="{{ $p->id }}"
                     data-nama="{{ $p->name_product }}"
                     data-harga="{{ $p->harga_jual }}"
                     data-stok="{{ $p->stok }}">
                    <div class="card-body text-center p-2">
                        <div class="bg-light mb-2 d-flex align-items-center justify-content-center" style="height:70px">
                            <i class="fas fa-box fa-2x text-secondary"></i>
                        </div>
                        <h6 class="mb-1 small fw-bold">{{ $p->name_product }}</h6>
                        <div class="text-primary small fw-bold">{{ number_format($p->harga_jual,0,',','.') }}</div>
                        <small class="text-muted" style="font-size: 10px;">Stok {{ $p->stok }}</small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center"><div class="alert alert-warning">Produk tidak tersedia</div></div>
            @endforelse
        </div>
    </div>

    {{-- ================= AREA KERANJANG ================= --}}
    <div class="col-lg-4 col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <h6 class="mb-3 fw-bold">🛒 Keranjang</h6>
                <form action="{{ route('kasir.store') }}" method="POST" id="main-form">
                @csrf
                
                <div id="cart" style="max-height: 300px; overflow-y: auto;">
                    <p class="text-center text-muted my-4 small">Belum ada produk</p>
                </div>
                
                <div id="hidden-input"></div>

                <hr class="my-2">

                <div class="bg-light p-2 rounded">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Subtotal</span>
                        <strong id="subtotal-text">0</strong>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <label class="small text-muted" style="font-size: 11px;">Disc Total (%)</label>
                            <input type="number" id="discount-total" class="form-control form-control-sm" value="0" min="0">
                        </div>
                        <div class="col-6">
                            <label class="small text-muted" style="font-size: 11px;">Pajak (%)</label>
                            <input type="number" id="tax" class="form-control form-control-sm" value="0" min="0">
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Akhir</span>
                        <h5 class="text-primary fw-bold m-0" id="total-text">0</h5>
                    </div>
                </div>

                <input type="hidden" name="total" id="total">

                <div class="mt-3">
                    <input type="number" id="bayar" name="bayar" class="form-control form-control-lg" placeholder="Nominal Bayar">
                </div>
                <div class="mt-2">
                    <input type="text" id="kembalian" class="form-control bg-light fw-bold text-end" placeholder="Kembalian" readonly>
                </div>

                <input type="hidden" name="metode_pembayaran" id="input-metode">
                <input type="hidden" name="rekening_bca" id="input-rekening">

                <div class="mt-3">
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary flex-fill metode-btn" data-metode="cash" id="btn-cash">Cash</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary flex-fill metode-btn" data-metode="qris" id="btn-qris">QRIS</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary flex-fill metode-btn" data-metode="Bank_bca" id="btn-bca">BCA</button>
                    </div>
                </div>

                {{-- Detail BCA (Muncul saat tombol BCA diklik) --}}
                <div id="bca-detail" style="display:none; margin-top:10px; padding:10px; background:#f0f7ff; border-radius:8px; border:1px solid #dbeafe;">
                    <label class="small fw-bold text-primary mb-1">Rekening Tujuan</label>
                    <select id="pilih-rekening" class="form-select form-select-sm mb-2" onchange="updateRekening(this)">
                        <option value="082192064246|Van Store">082192064246 – Van Store</option>
                        <option value="0987654321|Warung Karza">0987654321 – Warung Karza</option>
                    </select>
                    <div class="bg-white p-2 rounded small border">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">No. Rek:</span>
                            <strong id="norek-display">082192064246</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nama:</span>
                            <strong id="nama-display">Van Store</strong>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3 py-2 fw-bold" id="btn-submit" disabled>SIMPAN & CETAK</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

{{-- TEMPLATE STRUK --}}
<div id="receipt-section">
    <div style="text-align: center;">
        <h4 style="margin:0">Warung Karza</h4>
        <p style="font-size: 11px; margin:0;">Surabaya, Jawa Timur</p>
    </div>
    <hr style="border-top: 1px dashed #000; margin: 5px 0;">
    <div style="font-size: 11px;">
        Tgl: {{ date('d/m/Y H:i') }}<br>
        Kasir: Admin
    </div>
    <hr style="border-top: 1px dashed #000; margin: 5px 0;">
    <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
        <tbody id="receipt-items"></tbody>
    </table>
    <hr style="border-top: 1px dashed #000; margin: 5px 0;">
    <table style="width: 100%; font-size: 11px;">
        <tr><td>Subtotal</td><td align="right" id="r-subtotal"></td></tr>
        <tr><td>Disc Total</td><td align="right" id="r-disc-v"></td></tr>
        <tr><td>Pajak</td><td align="right" id="r-tax-v"></td></tr>
        <tr style="font-weight: bold;"><td>TOTAL</td><td align="right" id="r-total"></td></tr>
        <tr style="border-top: 1px solid #000;"><td>Bayar</td><td align="right" id="r-bayar"></td></tr>
        <tr><td>Kembali</td><td align="right" id="r-kembali"></td></tr>
    </table>
    <div style="text-align: center; font-size: 10px; margin-top: 10px;">Terima Kasih!</div>
</div>

@endsection

@push('script')
<script>
let cart = {};
let metodeBayar = '';

function formatNumber(angka){
    return Math.round(angka).toLocaleString('id-ID');
}

function formatRupiah(angka){
    return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
}

function renderCart(){
    let html = '';
    let hidden = '';
    let runningSubtotal = 0;

    let entries = Object.values(cart);
    if(entries.length === 0){
        $('#cart').html('<p class="text-center text-muted my-4 small">Belum ada produk</p>');
        updateTotals(0);
        return;
    }

    entries.forEach((item, i) => {
        let hrgDiscProduk = item.harga - (item.harga * (item.diskonProduk / 100));
        let subItem = item.qty * hrgDiscProduk;
        runningSubtotal += subItem;

        html += `
        <div class="border-bottom pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <strong class="small d-block text-truncate">${item.nama}</strong>
                    <span class="small text-muted" style="font-size: 11px;">${formatNumber(item.harga)}</span>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-xs btn-outline-danger minus p-0 px-1" data-id="${item.id}">-</button>
                    <span class="mx-2 small fw-bold">${item.qty}</span>
                    <button type="button" class="btn btn-xs btn-outline-primary plus p-0 px-1" data-id="${item.id}">+</button>
                </div>
            </div>
            <div class="row g-1 mt-1 align-items-center">
                <div class="col-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text p-1" style="font-size: 9px;">Disc%</span>
                        <input type="number" class="form-control p-1 disc-produk-input text-center" 
                               data-id="${item.id}" value="${item.diskonProduk}" min="0" max="100" style="font-size: 11px;">
                    </div>
                </div>
                <div class="col-6 text-end">
                    <span class="small fw-bold text-primary">${formatNumber(subItem)}</span>
                </div>
            </div>
        </div>`;

        hidden += `
            <input type="hidden" name="produk[${i}][produk_id]" value="${item.id}">
            <input type="hidden" name="produk[${i}][qty]" value="${item.qty}">
            <input type="hidden" name="produk[${i}][harga]" value="${item.harga}">
            <input type="hidden" name="produk[${i}][diskon_produk]" value="${item.diskonProduk}">
            <input type="hidden" name="produk[${i}][sub_total]" value="${subItem}">
        `;
    });

    $('#cart').html(html);
    $('#hidden-input').html(hidden);
    updateTotals(runningSubtotal);
}

function updateTotals(subtotal){
    let diskonTotalPercent = parseFloat($('#discount-total').val()) || 0;
    let pajakPercent       = parseFloat($('#tax').val()) || 0;

    let nominalDiskonTotal = subtotal * (diskonTotalPercent / 100);
    let setelahDiskon      = subtotal - nominalDiskonTotal;
    let nominalPajak       = setelahDiskon * (pajakPercent / 100);
    let total              = Math.round(setelahDiskon + nominalPajak);

    $('#subtotal-text').text(formatNumber(subtotal));
    $('#total-text').text(formatNumber(total));
    $('#total').val(total);

    if(metodeBayar !== 'cash' && metodeBayar !== '') {
        $('#bayar').val(total);
    }
    hitungKembalian();
}

function hitungKembalian(){
    let total = parseInt($('#total').val()) || 0;
    let bayar = parseInt($('#bayar').val()) || 0;
    let kembali = bayar - total;
    
    if(kembali >= 0) {
        $('#kembalian').val("Kembali: " + formatNumber(kembali)).addClass('text-success').removeClass('text-danger');
    } else {
        $('#kembalian').val("Kurang: " + formatNumber(Math.abs(kembali))).addClass('text-danger').removeClass('text-success');
    }
    
    $('#btn-submit').prop('disabled', !(total > 0 && bayar >= total && metodeBayar !== ''));
}

$('.produk-card').click(function(){
    let id = $(this).data('id');
    if(!cart[id]) {
        cart[id] = {
            id: id, nama: $(this).data('nama'), harga: parseInt($(this).data('harga')),
            qty: 1, diskonProduk: 0, stok: $(this).data('stok')
        };
    } else if(cart[id].qty < cart[id].stok) {
        cart[id].qty++;
    }
    renderCart();
});

$(document).on('click', '.plus', function(){
    let id = $(this).data('id');
    if(cart[id].qty < cart[id].stok){ cart[id].qty++; renderCart(); }
});

$(document).on('click', '.minus', function(){
    let id = $(this).data('id');
    cart[id].qty--;
    if(cart[id].qty <= 0) delete cart[id];
    renderCart();
});

$(document).on('input', '.disc-produk-input', function(){
    let id = $(this).data('id');
    let val = parseFloat($(this).val()) || 0;
    cart[id].diskonProduk = val > 100 ? 100 : val;
    renderCart();
});

$('#discount-total, #tax').on('input', function(){
    let sub = 0;
    Object.values(cart).forEach(item => {
        sub += (item.qty * (item.harga - (item.harga * (item.diskonProduk/100))));
    });
    updateTotals(sub);
});

$('#bayar').on('input', hitungKembalian);

$('.metode-btn').click(function(){
    metodeBayar = $(this).data('metode');
    $('.metode-btn').removeClass('btn-primary btn-success btn-info text-white').addClass('btn-outline-secondary');
    
    $('#bca-detail').hide(); // Sembunyikan dulu semua

    if(metodeBayar === 'cash') {
        $(this).addClass('btn-success text-white');
    } else if(metodeBayar === 'qris') {
        $(this).addClass('btn-primary text-white');
    } else if(metodeBayar === 'Bank_bca') {
        $(this).addClass('btn-info text-white');
        $('#bca-detail').slideDown(200); // Tampilkan detail rekening
        updateRekening(document.getElementById('pilih-rekening'));
    }
    
    $('#input-metode').val(metodeBayar);
    
    if(metodeBayar !== 'cash') $('#bayar').val($('#total').val()).prop('readonly', true);
    else $('#bayar').prop('readonly', false);
    
    hitungKembalian();
});

function updateRekening(sel){
    let parts = sel.value.split('|');
    $('#norek-display').text(parts[0]);
    $('#nama-display').text(parts[1]);
    $('#input-rekening').val(parts[0] + ' - ' + parts[1]);
}

$('#main-form').submit(function(e){
    e.preventDefault();
    let itemsHtml = '';
    Object.values(cart).forEach(item => {
        let hrgFinal = item.harga - (item.harga * (item.diskonProduk/100));
        itemsHtml += `<tr><td style="padding:2px 0;">${item.nama}<br>${item.qty} x ${formatRupiah(hrgFinal)}</td>
                      <td align="right" valign="bottom">${formatRupiah(item.qty*hrgFinal)}</td></tr>`;
    });
    
    let subtotalVal = parseFloat($('#subtotal-text').text().replace(/\./g, ''));
    let discTotalPercent = parseFloat($('#discount-total').val()) || 0;
    let discTotalNominal = subtotalVal * (discTotalPercent / 100);
    let taxPercent = parseFloat($('#tax').val()) || 0;
    let taxNominal = (subtotalVal - discTotalNominal) * (taxPercent / 100);

    $('#receipt-items').html(itemsHtml);
    $('#r-subtotal').text(formatRupiah(subtotalVal));
    $('#r-disc-v').text('- ' + formatRupiah(discTotalNominal));
    $('#r-tax-v').text('+ ' + formatRupiah(taxNominal));
    $('#r-total').text(formatRupiah($('#total').val()));
    $('#r-bayar').text(formatRupiah($('#bayar').val()));
    $('#r-kembali').text(formatRupiah(Math.max(0, $('#bayar').val() - $('#total').val())));

    window.print();
    setTimeout(() => { this.submit(); }, 1000);
});
</script>
@endpush