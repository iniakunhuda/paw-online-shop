@extends('admin.layout')

@section('title')
    Tambah Artikel
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Tambah Artikel
            </div>
            <div class="card-body">
                <div class="">
                    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Judul Artikel</label>
                                <input type="text" class="form-control" name="judul" placeholder="Judul" required>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Konten</label>
                                <textarea cols="20" class="form-control text-editor" id="editor1" rows="5" name="desc" required></textarea>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Kategori</label>
                                <input type="text" class="form-control" name="kategori" placeholder="Kategori">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Cover Artikel</label>
                                <input type="file" name="photo" placeholder="Photo">
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
    CKEDITOR.replace( 'editor1' );
</script>    
@endpush