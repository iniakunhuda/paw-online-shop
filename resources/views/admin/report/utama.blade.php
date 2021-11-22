@extends('admin.layout')

@section('title')
    Laporan Utama
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 mb-2">

                <div class="row">
                    <div class="col-12">
                        <h1 class="mt-3 mb-0">Laporan Utama</h1>
                        <p>Laporan dalam 1 bulan</p>
                    </div>
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <h4 class="card-title mb-0">Ubah Filter</h4>
                            </div>
                            <div class="card-body collapse" id="collapseExample">
                                <form method="GET" action="">
                                    <div class="mb-2">
                                        <p class="text-black">Pilih Tanggal Pesan</p>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <small>Awal</small>
                                                <div class="form-group">
                                                    <input type="date" name="awal" class="form-control" placeholder="Awal" value="{{ date('Y-m-d', $start) }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <small>Akhir</small>
                                                <div class="form-group">
                                                    <input type="date" name="akhir" class="form-control" placeholder="akhir" value="{{ date('Y-m-d', $end) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Pilih Produsen</p>
                                                <div class="form-group">
                                                    <select name="produsen" class="form-control">
                                                        <option value="">Semua Produsen</option>
                                                        @foreach($_merchants as $id => $prod)
                                                        <option value="{{ $prod['_id'] }}" {{ (isset($_GET['produsen']) && $_GET['produsen'] == $prod['_id']) ? 'selected' : '' }}>{{ $prod['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Pilih Status</p>
                                                <select name="status" class="form-control">
                                                    <option value="">Semua Status</option>
                                                    <option value="pending" {{ (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' }}>
                                                        Menunggu konfirmasi Admin
                                                    </option>
                                                    <option value="in_progress" {{ (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : '' }}>
                                                        Pesanan sedang diproses oleh Admin
                                                    </option>
                                                    <option value="done" {{ (isset($_GET['status']) && $_GET['status'] == 'done') ? 'selected' : '' }}>
                                                        Pesanan selesai
                                                    </option>
                                                    <option value="cancel" {{ (isset($_GET['status']) && $_GET['status'] == 'cancel') ? 'selected' : '' }}>
                                                        Pesanan dibatalkan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Kategori Produk</p>
                                                <div class="form-group">
                                                    <select name="kategori" class="form-control">
                                                        <option value="">Semua Kategori</option>
                                                        @foreach($cats as $id => $cat)
                                                        <option value="{{ $cat['_id'] }}" {{ (isset($_GET['kategori']) && $_GET['kategori'] == $cat['_id']) ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Nama Produk</p>
                                                <div class="form-group">
                                                    <input type="text" name="nama" class="form-control" placeholder="Nama Produk" value="{{ (isset($_GET['nama'])) ? $_GET['nama'] : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-block mt-3">Ubah Filter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table responsive table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Nomor Nota</th>
                                                <th>Order Pada</th>
                                                <th>Bayar Pada</th>
                                                <th>Rencana Pengiriman</th>
                                                <th>Nama Produsen</th>
                                                <th>Jenis</th>
                                                <th>Nama Produk</th>
                                                <th class="text-end">Harga Dasar</th>
                                                <th>Unit</th>
                                                <th class="text-end">Harga Jual</th>
                                                <th class="text-end">Total Harga Jual</th>
                                                <th class="text-end">Penerimaan Produsen</th>
                                                <th class="text-end">Penerimaan Paguyuban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count_row = 0;
                                                $total_all = 0;
                                                $total_produsen = 0;
                                                $total_paguyuban = 0;
                                            @endphp

                                            @if(count($trans) > 0)
                                                @foreach($trans as $idx_trans => $r)
                                                    @php
                                                        $no_inv = "";
                                                        $i = $r['products'];

                                                        $subtotal = $i['qty'] * $i['price'];
                                                        $produsen = $i['merchant_price'] * $i['qty'];
                                                        $paguyuban = $subtotal - $produsen;

                                                        $total_all += $subtotal;
                                                        $total_produsen += $produsen;
                                                        $total_paguyuban += $paguyuban;

                                                        $count_row += 1;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            @if($no_inv != $r['invno'])
                                                                {{ $r['invno'] }}
                                                            @endif
                                                            @php
                                                                $no_inv = $r['invno'];
                                                            @endphp
                                                        </td>
                                                        <td class="text-center">{{ ($r['date_history']['pesan'] != null) ? \Carbon\Carbon::parse($r['date_history']['pesan'])->format('d M Y') : '-' }}</td>
                                                        <td class="text-center">{{ ($r['date_history']['bayar'] != null) ? \Carbon\Carbon::parse($r['date_history']['bayar'])->format('d M Y') : '-' }}</td>
                                                        <td class="text-center">{{ ($r['delivery_time'] != null) ? \Carbon\Carbon::parse($r['delivery_time'])->format('d M Y') : '-' }}</td>
                                                        <td class="text-center">{{ $_merchants[$i['merchant']]['name'] ?? '-' }}</td>
                                                        <td class="text-center">{{ $_category[$i['category']]['name'] ?? '-' }}</td>
                                                        <td class="text-center">{{ $i['name'] }}</td>
                                                        <td class="text-end">@currency($i['merchant_price'])</td>
                                                        <td class="text-center">{{ $i['qty'] }}</td>
                                                        <td class="text-end">@currency($i['price'])</td>
                                                        <td class="text-end">
                                                            @currency($subtotal)
                                                        </td>
                                                        <td class="text-end">
                                                            @currency($produsen)
                                                        </td>
                                                        <td class="text-end">
                                                            @currency($paguyuban)
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr style="background: #f9f9f9">
                                                <td colspan="10" class="text-center" style="font-weight:800">TOTAL</td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td style="display:none"></td>
                                                <td class="text-end" style="font-weight:800">@currency($total_all)</td>
                                                <td class="text-end" style="font-weight:800">@currency($total_produsen)</td>
                                                <td class="text-end" style="font-weight:800">@currency($total_paguyuban)</td>
                                            </tr>
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
<script>
$(document).ready( function () {

    var count_row = {{ $count_row+3 }};
    var excelCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    return (column === 7 || column === 9 || column === 10 || column === 11 || column === 12) ?
                        data.replace( /[Rp.]/g, '' ) :
                        data;
                }
            }
        }
    };

    $('table').DataTable({
        paging: false,
        info: false,
        ordering: false,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            $.extend( true, {}, excelCommon, {
                extend: 'excelHtml5',
                title: "Laporan Transaksi {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}",
                customize: function( xlsx ) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('c[r=A'+count_row+']', sheet).attr( 's', '2' );
                    $('c[r=K'+count_row+']', sheet).attr( 's', '2' ); // harga total
                    $('c[r=L'+count_row+']', sheet).attr( 's', '2' ); // penerimaan produsen
                    $('c[r=M'+count_row+']', sheet).attr( 's', '2' ); // paguyuban
                }
            }),
            $.extend( true, {}, null, {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: "Laporan Transaksi {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}"
            })
        ]
    });
});
</script>
@endpush
