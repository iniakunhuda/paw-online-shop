@extends('admin.layout')

@section('title')
    List Produk
@endsection

@section('content')
<div id="appCapsule">
    <br>
    <br>
    <div class="section mb-5">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    List Produk
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Buat Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga Jual</th>
                                <th scope="col">Harga Beli</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Pedagang</th>
                                <th scope="col">Minimal Order</th>
                                <th >Catatan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <th scope="row">{{ $loop->iteration }} </th>
                                <td>{{ $product->name }} </td>
                                <td>
                                    @currency($product->price['discount_price'])
                                    @if($product->price['discount_price'] != $product->price['real_price'])
                                        <br><del class="text-muted">@currency($product->price['real_price'])</del>
                                    @endif
                                </td>
                                @if(isset($product->price['merchant_price']))
                                <td>@currency($product->price['merchant_price'])</td>
                                @else
                                <td><span class="text-danger">Belum diset</span></td>
                                @endif
                                
                                @if(isset($product->parent_category))
                                <td>
                                    Kategori : {{ $categories[$product->parent_category]['name'] ?? 'Tidak ditemukan' }}<br><br>
                                    Subkategori : {{ $categories[$product->category]['name'] ?? 'Tidak ditemukan' }}
                                </td>
                                @else
                                <td><span class="text-danger">Belum diset</span></td>
                                @endif

                                <td>{{ (isset($merchant[$product->merchant])) ? $merchant[$product->merchant]['name'] : '-' }}</td>
                                @if(isset($product->min_order))
                                    <td>{{ $product->min_order }}</td>
                                @else
                                    <td><span class="text-danger">Belum diset</span></td>
                                @endif
                                <td>{{ $product->note }}</td>
                                <td>
                                    <a href="{{ route('admin.products.photo.index', [$product->_id]) }}" class="btn btn-success mt-1 mb-1">Photo</a>
                                    <a href="{{ route('admin.products.edit', [$product->_id]) }}" class="btn btn-primary mt-1 mb-1">Ubah</a>
                                    <a href="javascript:void(0);" 
                                        onclick="deleteRow('{{ $product->id }}', '{{$product->nama}}')" 
                                        class="btn btn-danger mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</a>
                                    <form id="form_delete_{{$product->id}}" action="{{ route('admin.products.destroy', [$product->id]) }}" method="POST">
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