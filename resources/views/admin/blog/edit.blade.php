@extends('admin.layout')

@section('title')
    Edit Artikel
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Edit Artikel
            </div>
            <div class="card-body">
                <div class="">
                    <form action="{{ route('admin.blog.update', [$blog->_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Judul Artikel</label>
                                <input type="text" class="form-control" name="judul" placeholder="Judul" value="{{ $blog->title }}" required>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Konten</label>
                                <textarea cols="20" class="form-control text-editor" id="editor1" rows="5" name="desc" required>{{ $blog->desc }}</textarea>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Kategori</label>
                                <input type="text" class="form-control" name="kategori" placeholder="Kategori" value="{{ $blog->category }}">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Cover Artikel</label>
                                @if(isset($blog['img']) && ($blog['img'] != ""))
                                    <img class="img-fluid" src="{{ url(\Config::get('website.url_blog').$blog['img']) }}" style="width:20%" alt=""><br><br>
                                @else
                                    <img class="img-fluid" style="width:20%" src="{{ url('img/logo.png') }}" alt>
                                @endif
                                <input type="file" name="photo" placeholder="Photo">
                                <input type="hidden" class="form-control" name="photo_old" value="{{ $blog['img'] }}">

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