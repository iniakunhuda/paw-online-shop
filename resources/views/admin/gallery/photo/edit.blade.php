@extends('admin.layout')

@section('title')
    Edit Gambar
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.gallery.photo.index',[$id_product]) }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Edit Gambar
            </div>
            <div class="card-body">
                <div class="">
                    <form class="" action="{{ route('admin.gallery.photo.update',[$id_product, $photo['id']]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Judul</label>
                                <input type="text" class="form-control" name="judul" value="{{ $photo['title'] }}">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            @if(isset($photo['url']) && ($photo['url'] != ""))
                                <img class="img-fluid" src="{{ url(\Config::get('website.url_galeri').$photo['url']) }}" alt=""><br><br>
                            @else
                                <img class="img-fluid" style="width:20%" src="{{ url('img/logo.png') }}" alt>
                            @endif
                            <div class="input-wrapper">
                                <label class="label">Gambar</label>
                                <input type="file" name="photo" placeholder="Photo">
                                <input type="hidden" class="form-control" name="photo_old" value="{{ $photo['url'] }}">
                            </div>
                        </div>


                        <div class="form-group boxed">
                            <button type="submit" name ="submit" class="btn btn-primary btn-block btn-lg" >SIMPAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($errors->any())
        <br>
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif

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