@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">

    {{-- PRODUK --}}
    <div class="col-md-9">
        <h5 class="mb-3">Kasir POS</h5>

        <div class="row" id="produk-list">
            @foreach($products as $p)
            <div class="col-md-3 mb-3">
                <div class="card produk-card"
                     data-id="{{ $p->id }}"
                     data-nama="{{ $p->nama_produk }}"
                     data-harga="{{ $p->harga_jual }}"
                     data-stok="{{ $p->stok }}">
                    <div class="card-body text-center">
                        <div class="bg-secondary mb-2" style="height:80px"></div>
                        <strong>Rp {{ number_format($p->harga_jual) }}</strong><br>
                        <small>Stok {{ $p->stok }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- KERANJANG --}}
    <div class="col-md-3">
        <h6>🛒 Keranjang</h6>

        <form action="{{ route('kasir.store') }}" method="POST" id="form-kasir">
        @csrf

        <div id="cart"></div>
        <div id="hidden-input"></div>

        <hr>

        <h6>Total</h6>
        <h4 id="total-text">Rp 0</h4>
        <input type="hidden" name="total" id="total">

        <div class="btn-group w-100 mb-2">
            <button type="button" class="btn btn-success active" id="btn-cash">💵 Cash</button>
            <button type="button" class="btn btn-secondary" id="btn-qris">📱 QRIS</button>
        </div>

        <input type="number" class="form-control mb-2"
               id="bayar" name="bayar" placeholder="Uang bayar">

        <input type="text" class="form-control mb-2"
               id="kembalian" placeholder="Kembalian" readonly>

        <button type="submit"
                class="btn btn-primary w-100"
                id="btn-submit"
                disabled>
            💾 Simpan & Cetak
        </button>

        </form>
    </div>

</div>
</div>
@endsection

@push('script')
<script>
let cart = {};
let metode = 'cash';

function renderCart(){
    let html = '';
    let total = 0;
    let hidden = '';

    Object.values(cart).forEach((item, i) => {
        let sub = item.qty * item.harga;
        total += sub;

        html += `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                ${item.nama}<br>
                <small>Rp ${item.harga}</small>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-light minus" data-id="${item.id}">-</button>
                ${item.qty}
                <button type="button" class="btn btn-sm btn-light plus" data-id="${item.id}">+</button>
            </div>
        </div>
        `;

        hidden += `
            <input type="hidden" name="items[${i}][produk_id]" value="${item.id}">
            <input type="hidden" name="items[${i}][qty]" value="${item.qty}">
            <input type="hidden" name="items[${i}][harga]" value="${item.harga}">
            <input type="hidden" name="items[${i}][subtotal]" value="${sub}">
        `;
    });

    $('#cart').html(html);
    $('#hidden-input').html(hidden);
    $('#total').val(total);
    $('#total-text').text('Rp ' + total.toLocaleString());

    $('#btn-submit').prop('disabled', total === 0);
    hitungKembalian();
}

function hitungKembalian(){
    let total = parseInt($('#total').val()) || 0;
    let bayar = parseInt($('#bayar').val()) || 0;

    if(metode === 'cash'){
        $('#kembalian').val(bayar - total);
        $('#btn-submit').prop('disabled', bayar < total || total === 0);
    } else {
        $('#kembalian').val(0);
        $('#bayar').val(total);
        $('#btn-submit').prop('disabled', total === 0);
    }
}

$('.produk-card').click(function(){
    let id = $(this).data('id');
    let stok = $(this).data('stok');

    if(!cart[id]){
        cart[id] = {
            id: id,
            nama: $(this).data('nama'),
            harga: $(this).data('harga'),
            qty: 1,
            stok: stok
        };
    } else {
        if(cart[id].qty < stok){
            cart[id].qty++;
        }
    }
    renderCart();
});

$(document).on('click','.plus',function(){
    let id = $(this).data('id');
    if(cart[id].qty < cart[id].stok){
        cart[id].qty++;
        renderCart();
    }
});

$(document).on('click','.minus',function(){
    let id = $(this).data('id');
    cart[id].qty--;
    if(cart[id].qty <= 0) delete cart[id];
    renderCart();
});

$('#bayar').on('input', hitungKembalian);

$('#btn-cash').click(function(){
    metode = 'cash';
    $(this).addClass('active');
    $('#btn-qris').removeClass('active');
    $('#bayar').prop('readonly', false).val('');
    hitungKembalian();
});

$('#btn-qris').click(function(){
    metode = 'qris';
    $(this).addClass('active');
    $('#btn-cash').removeClass('active');
    $('#bayar').prop('readonly', true);
    hitungKembalian();
});
</script>
@endpush
