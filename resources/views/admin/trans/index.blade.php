@extends('admin.layout')

@section('title')
    Transaksi Terbaru
@endsection

@section('content')
<div id="appCapsule">
    <br>
    <br>
    <div class="section mb-5">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Transaksi Terbaru
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nomor Transaksi</th>
                                <th scope="col">Detail Pembeli</th>
                                <th scope="col">Tanggal Rencana Kirim</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($trans as $t)
                            <tr class="data-row">
                                <th scope="row">{{ $loop->iteration }}  </th>
                                <td class="name"> {{ $t->invno }} </td>
                                <td class="name">
                                    {{ $t->buyer['name'] }} <br>
                                    {{ $t->buyer['whatsapp'] }}
                                </td>
                                @if($t->delivery_time != "")
                                <td class="name"> 
                                    {{ \Carbon\Carbon::createFromTimestamp($t['delivery_time'])->format('d M Y H:i') }}
                                </td>
                                @else
                                <td>-</td>
                                @endif
                                <td class="name"> @currency($t->price['total']) </td>
                                <td class="name"> 
                                    @php
                                    switch ($t->status) {
                                        case 'pending':
                                            echo "Menunggu konfirmasi Admin";
                                            break;
                
                                        case 'in_progress':
                                            echo "Pesanan sedang diproses oleh Admin";
                                            break;
                
                                        case 'done':
                                            echo "Pesanan selesai";
                                            break;
                
                                        case 'cancel':
                                            echo "Pesanan dibatalkan";
                                            break;
                                        
                                        default:
                                            echo "Menunggu konfirmasi Admin";
                                            break;
                                    }    
                                @endphp
                                </td>
                                <td>
                                    <a href="{{ route('admin.trans.edit', [$t->_id]) }}" class="btn btn-primary mt-1 mb-1">Detail</a>
                                    {{-- <a href="javascript:void(0);" 
                                        onclick="deleteRow('{{ $t->id }}', '{{$t->nama}}')" 
                                        class="btn btn-danger mt-1 mb-1"><i class="fa fa-trash"></i> Hapus</a>
                                    <form id="form_delete_{{$t->id}}" action="{{ route('admin.trans.destroy', [$t->id]) }}" method="POST">
                                    {{ csrf_field() }}  
                                    <input type="hidden" name="_method" value="DELETE">
                                    </form> --}}
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
});
</script>    
@endpush