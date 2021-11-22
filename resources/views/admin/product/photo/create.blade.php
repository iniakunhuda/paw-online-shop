@extends('admin.layout')

@section('title')
    Tambah Gambar Produk
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.products.photo.index',[$id_product]) }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Tambah Gambar Produk
            </div>
            <div class="card-body">
                <div class="">
                    <form class="" action="{{ route('admin.products.photo.store',[$id_product]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Judul</label>
                                <input type="text" class="form-control" name="judul">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Gambar</label>
                                <input type="file" name="photo" placeholder="Photo">
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
</script>    
@endpush