@extends('admin.layout')

@section('title')
    Detail transaksi user
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 mb-2">

                <div class="row">
                    <div class="col-12">
                        <h1 class="mt-3 mb-2">Detil transaksi user</h1>
                    </div>
                    <div class="col-12">
                        <div class="card mb-2" style="padding-top: 10px; padding-bottom: 10px; padding-left: 10px">
                            @foreach($trans as $tr => $x)
                            <h4>Nama : {{ $x['nama'] }} </h4>
                            <h4>Alamat : {{ $x['alamat'] }} </h4>
                            <h4>Nomor Telp : {{ $x['whatsapp'] }} </h4>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table responsive table-condensed">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal pembelian</th>
                                                <th>Nomor nota</th>
                                                <th>Produk pembelian</th>
                                                <th class="text-end">Total Pembayaran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $i = 0;
                                            @endphp
                                            @foreach($trans as $tr => $dt)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ ($dt['tgl_transaksi'] != null) ? \Carbon\Carbon::parse($dt['tgl_transaksi'])->format('d M Y') : '-' }}</td>
                                                <td>{{ $dt['invno'] }}</td>
                                                <td>
                                                    @php
                                                    $j = 0;
                                                    @endphp
                                                    @foreach($dt['produk'] as $pd => $prd)
                                                    <ul>
                                                        <li><b>No. </b>{{ ++$j }}</li>
                                                        <li><b>Nama produk</b>: {{ $prd['name'] }}</li>
                                                        <li><b>Jumlah pembelian</b>: {{ $prd['qty'] }} buah</li>
                                                        <li><b>Harga</b>: Rp. {{ $prd['subtotal'] }}</li>
                                                    </ul>
                                                    @endforeach
                                                </td>
                                                <td class="text-end">@currency($dt['total_bayar'])</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<br>
@endsection


@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
@endpush

@push('js')
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/natural.js"></script>
@endpush
