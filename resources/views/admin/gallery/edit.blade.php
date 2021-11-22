@extends('admin.layout')

@section('title')
    Edit Gallery
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="card">
            <div class="card-header">
                Edit Gallery
            </div>
            <div class="card-body">
                <div class="">
                    <form action="{{ route('admin.gallery.update', [$gallery->_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Judul</label>
                                <input type="text" class="form-control" name="title" placeholder="Nama" required value="{{ $gallery->title }}">
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label">Tanggal</label>
                                <input type="date" class="form-control" name="date" placeholder="Tanggal" required value="{{ \Carbon\Carbon::parse($gallery->date)->format('Y-m-d') }}">
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