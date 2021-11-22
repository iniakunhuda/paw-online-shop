@extends('admin.layout')

@section('title')
    Tambah Produk
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.products.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Tambah Produk
            </div>
            <div class="card-body">
                <div class="">
                    <form class="" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                        <div class="dropdown">
                            <div class="form-group boxed">
                                <label class="label">Unit Dagang</label>
                                <select name="merchant" class="form-control" required>
                                    <option value="">--- Pilih Unit Dagang ---</option>
                                    @foreach($merchants as $m)
                                    <option value="{{ $m->_id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="dropdown">
                            <div class="form-group boxed">
                                <label class="label">Kategori Produk</label>
                                <select id="parent_category" class="form-control" onchange="changeParentCategory(this)" name="parent_category" required>
                                    <option value="">--- Pilih Kategori Utama ---</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->_id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <select id="category" class="form-control mt-1" name="category">
                                    <option value="">--- Pilih Subkategori ---</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Nama Produk</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Deskripsi Singkat</label>
                                <textarea cols="20" class="form-control text-editor" id="editor1" rows="5" name="desc" required></textarea>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Deskripsi Lengkap</label>
                                <textarea cols="20" class="form-control text-editor" id="editor2" rows="10" name="desc_long" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label">Harga Jual (Sebelum Diskon)</label>
                                        <input type="text" class="form-control"  name="price[real_price]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group boxed">
                                    <div class="input-wrapper">
                                        <label class="label">Harga Jual (Setelah Diskon)</label>
                                        <input type="text" class="form-control"  name="price[discount_price]" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Harga Beli dari Produsen Kue</label>
                                <input type="text" class="form-control" name="price[merchant_price]" value="" required>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-wrapper">
                                        <label class="label">Minimal Order</label>
                                        <input type="text" class="form-control" name="min_order" value="1" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-wrapper">
                                        <label class="label">Minimum pesan H-berapa (Misal h-3, isi dengan 3. Isi 0 jika tidak ada minimal order berapa hari)</label>
                                        <input type="number" class="form-control" name="min_hari" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Catatan Produk</label>
                                <input type="text" class="form-control"  name="note">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <button type="submit" name ="submit" class="btn btn-primary btn-block btn-lg" >SIMPAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
@endsection

@push('js')
<script>
    CKEDITOR.replace( 'editor1' );
    CKEDITOR.replace( 'editor2' );

    function changeParentCategory(that, init_category=null) {
        let url = API_URL + "get_subcategory/" + $(that).val();
        axios.get(url)
            .then(function (response) {
                let status = response.data.status;
                if(status == 200) {
                    let data = response.data.data;
                    $('#category').html("<option value=''>--- Pilih Subkategori ---</option>");
                    data.forEach((r) => {
                        $('<option/>')
                            .val(r._id)
                            .text(r.name)
                            .appendTo('#category')
                    });
                    if(init_category != null) $('#category').val(init_category);
                } else {
                    showAlert(error);
                }
            })
            .catch(function (error) {
                console.log(error);
                showAlert(error);
            });  
    }
</script>    
@endpush