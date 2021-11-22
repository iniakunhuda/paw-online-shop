@extends('admin.layout')

@section('title')
    List Produk
@endsection

@section('content')
<div id="appCapsule">
    <br>
    <br>
    <div class="section">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    List Gambar Produk
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.products.photo.create',[$id_product]) }}" class="btn btn-primary">Buat Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Judul</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($photos as $photo)
                            <tr>
                                <th scope="row">{{ $loop->iteration }} </th>
                                <td>{{ $photo['title'] }}</td>
                                <td>
                                    @if($photo['url'] == "")
                                        <img class="img-fluid" style="width:20%" src="{{ url('img/logo.png') }}" alt>
                                    @else
                                        <img class="img-fluid" style="width:20%" src="{{ url(\Config::get('website.url_photo').$photo['url']) }}" alt>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.photo.edit', [$id_product,$photo->id]) }}" class="btn btn-primary mt-1 mb-1">Ubah</a>
                                    <a href="javascript:void(0);" 
                                        onclick="deleteRow('{{ $photo->id }}', '{{$photo->title}}')" 
                                        class="btn btn-danger mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</a>
                                    <form id="form_delete_{{$photo->id}}" action="{{ route('admin.products.photo.destroy', [$id_product,$photo->id]) }}" method="POST">
                                    {{ csrf_field() }}  
                                    <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('js')
<script>
$(document).ready( function () {
    $('table').DataTable();
} );
</script>    
@endpush