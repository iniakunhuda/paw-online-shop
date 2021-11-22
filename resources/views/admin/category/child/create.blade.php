@extends('admin.layout')

@section('title')
    Tambah Subkategori dari {{ $parent['name'] }}
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.categories.child.index',[$parent->_id]) }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Tambah Subkategori dari {{ $parent['name'] }}
            </div>
            <div class="card-body">
                <div class="">
                    <form action="{{ route('admin.categories.child.store',[$parent->_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Nama Kategori</label>
                                <input type="text" class="form-control" name="name" placeholder="Nama" required>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Urutan Kategori</label>
                                <input type="text" class="form-control" name="urutan" placeholder="Urutan. Contoh: 1" required>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">SIMPAN</button>
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