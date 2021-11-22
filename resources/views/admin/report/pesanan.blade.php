@extends('admin.layout')

@section('title')
    Laporan Pesanan
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 mb-2">

                <div class="row">
                    <div class="col-12">
                        <h1 class="mt-3 mb-0">Laporan Pesanan</h1>
                        <p>Laporan yang belum dibayar dalam 1 bulan</p>
                    </div>
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <h4 class="card-title mb-0">Ubah Filter</h4>
                            </div>
                            <div class="card-body collapse" id="collapseExample">
                                <form method="GET" action="">
                                    <div class="mb-2">
                                        <p class="text-black">Pilih Tanggal Pengiriman</p>
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
                                                <p class="text-black">Pilihan Produsen</p>
                                                <div class="form-group mb-2">
                                                    <select name="produsen" class="form-control">
                                                        <option value="">Semua Produsen</option>
                                                        @foreach($_merchants as $id => $prod)
                                                        <option value="{{ $prod['_id'] }}" {{ (isset($_GET['produsen']) && $_GET['produsen'] == $prod['_id']) ? 'selected' : '' }}>{{ $prod['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Nama Pembeli</p>
                                                <div class="form-group">
                                                    <input type="text" name="nama" class="form-control" placeholder="Nama Pembeli" value="{{ (isset($_GET['nama'])) ? $_GET['nama'] : '' }}">
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
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table responsive table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Rencana Kirim</th>
                                                <th>Tanggal Pesan</th>
                                                <th>Nomor Nota</th>
                                                <th>Nama Pembeli</th>
                                                <th>Nomor Telp</th>
                                                <th class="text-end">Total Bayar</th>
                                                <th>Status Pesanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count_row = 0;
                                            @endphp
                                            @if(count($trans) > 0)
                                            @foreach($trans as $idx_trans => $dt)
                                            @php
                                                        $count_row += 1;
                                            @endphp
                                            <tr>
                                            <td class="text-center">{{ ($dt['delivery_time'] != null) ? \Carbon\Carbon::createFromTimestamp($dt['delivery_time'])->format('d M Y H:i') : '-' }}</td>
                                            <td class="text-center">{{ ($dt['tgl_pesan'] != null) ? \Carbon\Carbon::parse($dt['tgl_pesan'])->format('d M Y') : '-' }}</td>
                                                        <td>{{ $dt['invno'] }}</td>
                                                        <td>{{ $dt['name'] }}</td>
                                                        <td>{{ $dt['whatsapp'] }}</td>
                                                        <td class="text-end">@currency($dt['total_bayar'])</td>
                                                        <td>
                                                            @php
                                                            if($dt['status'] === 'done'){
                                                                echo 'Pesanan selesai';
                                                            } else if ($dt['status'] === 'in_progress'){
                                                                echo 'Pesanan sedang diproses oleh Admin';
                                                            } else if ($dt['status'] === 'cancel'){
                                                                echo 'Pesanan dibatalkan';
                                                            } else {
                                                                echo 'Menunggu konfirmasi Admin';
                                                            }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                            @endforeach
                                            @endif
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
<script>
$(document).ready( function () {

    var count_row = {{ $count_row+3 }};
    var excelCommon = {
        exportOptions: {
            format: {
                body: function ( data, row, column, node ) {
                    return (column === 5) ?
                        data.replace( /[Rp.]/g, '' ) :
                        data;
                }
            }
        }
    };

    $('table').DataTable({
        paging: false,
        info: false,
        responsive: true,
        dom: 'Bfrtip',
        columnDefs: [
            { type: 'natural', targets: 5 },
        ],
        buttons: [
            $.extend( true, {}, excelCommon, {
                extend: 'excelHtml5',
                title: "Laporan Status Pesanan {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}",
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('c[r=A'+count_row+']', sheet).attr( 's', '2' );
                    $('c[r=B'+count_row+']', sheet).attr( 's', '2' ); // tanggal kirim
                    $('c[r=C'+count_row+']', sheet).attr( 's', '2' ); // tanggal pesan
                    $('c[r=D'+count_row+']', sheet).attr( 's', '2' ); // nomor nota
                    $('c[r=E'+count_row+']', sheet).attr( 's', '2' ); // nama pembeli
                    $('c[r=F'+count_row+']', sheet).attr( 's', '2' ); // nomor telp
                    $('c[r=G'+count_row+']', sheet).attr( 's', '2' ); // total bayar
                    $('c[r=H'+count_row+']', sheet).attr( 's', '2' ); // status pembayaran
                }
            }),
            $.extend( true, {}, null, {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: "Laporan Status Pesanan {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}"
            })
        ]
    });
});
</script>

@endpush
