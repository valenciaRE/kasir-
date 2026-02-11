<div>
    <button type="button"
        class="btn {{ $id ? 'btn-warning' : 'btn-primary' }}"
        data-toggle="modal"
        data-target="#formProduct{{ $id ?? '' }}">
        @if ($id)
        <i class="fas fa-edit"></i>
        @else
        Product Baru
        @endif
    </button>

    <div class="modal fade" id="formProduct{{ $id ?? '' }}">
        <form action="{{ route('master-data.product.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $id ?? '' }}">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $id ? 'Form Edit Product' : 'Form Product Baru' }}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group my-1">
                            <label>Nama Product</label>
                            <input type="text"
                                   name="name_product"
                                   class="form-control"
                                   value="{{ old('name_product', $name_product ?? '') }}">
                        </div>

                        <div class="form-group my-1">
                            <label>Kategori Product</label>
                            <select name="kategori_id" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kategori_id', $kategori_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group my-1">
                            <label>Harga Jual</label>
                            <input type="number"
                                   name="harga_jual"
                                   class="form-control"
                                   value="{{ old('harga_jual', $harga_jual ?? '') }}">
                        </div>

                        <div class="form-group my-1">
                            <label>Harga Beli Pokok</label>
                            <input type="number"
                                   name="harga_beli_pokok"
                                   class="form-control"
                                   value="{{ old('harga_beli_pokok', $harga_beli_pokok ?? '') }}">
                        </div>

                        <div class="form-group my-1">
                            <label>Stok</label>
                            <input type="number"
                                   name="stok"
                                   class="form-control"
                                   value="{{ old('stok', $stok ?? '') }}">
                        </div>

                        <div class="form-group my-1">
                            <label>Stok Minimal</label>
                            <input type="number"
                                   name="stok_minimal"
                                   class="form-control"
                                   value="{{ old('stok_minimal', $stok_minimal ?? '') }}">
                        </div>

                        <div class="form-group my-1">
                            <label class="mr-3">Produk Aktif?</label>
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $is_active ?? false) ? 'checked' : '' }}>
                            <small class="text-secondary d-block">
                                Jika aktif maka product tampil di kasir
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
