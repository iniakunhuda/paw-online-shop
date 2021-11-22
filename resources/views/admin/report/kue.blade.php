@extends('admin.layout')

@section('title')
    Laporan Kue
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 mb-2">

                <div class="row">
                    <div class="col-12">
                        <h1 class="mt-3 mb-0">Laporan Kue</h1>
                        <p>Laporan yang sudah selesai dalam 1 bulan</p>
                    </div>
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                <h4 class="card-title mb-0">Ubah Filter</h4>
                            </div>
                            <div class="card-body collapse" id="collapseExample">
                                <form method="GET" action="">
                                    <div class="mb-2">
                                        <p class="text-black">Pilih Tanggal Pesanan</p>
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
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <p class="text-black">Kategori Produk</p>
                                                <div class="form-group mb-2">
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
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table responsive table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Nama Kue</th>
                                                <th>Nama Unit Dagang</th>
                                                <th class="text-end">Penerimaan Produsen</th>
                                                <th class="text-end">Penerimaan Paguyuban</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count_row = 0;
                                            @endphp
                                            @if(count($trans) > 0)
                                                @foreach($trans as $dt)
                                                    @php
                                                        $count_row += 1;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $_items[$dt['id_item']]['name'] }}</td>
                                                        <td>{{ $_merchants[$dt['merchant']]['name'] }}</td>
                                                        <td class="text-end">@currency($dt['total_produsen'])</td>
                                                        <td class="text-end">@currency($dt['total_paguyuban'])</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: #f9f9f9" class="no-sort">
                                                <td style="font-weight:800" colspan="2">TOTAL</td>
                                                <td style="display: none"></td>
                                                <td class="text-end"  style="font-weight:800">@currency($total_produsen)</td>
                                                <td class="text-end"  style="font-weight:800">@currency($total_paguyuban)</td>
                                            </tr>
                                        </tfoot>
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
                    return (column === 1 || column === 2) ?
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
            { type: 'natural', targets: 1 },
            { type: 'natural', targets: 2 },
        ],
        buttons: [
            $.extend( true, {}, excelCommon, {
                extend: 'excelHtml5',
                footer:true,
                title: "Laporan Kue {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}",
                customize: function( xlsx ) {
                   var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('c[r=A'+count_row+']', sheet).attr( 's', '2' );
                    $('c[r=B'+count_row+']', sheet).attr( 's', '2' ); // produsen
                    $('c[r=C'+count_row+']', sheet).attr( 's', '2' ); // paguyuban
                }
            }),
            $.extend( true, {}, null, {
                extend: 'pdfHtml5',
                footer:true,
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: "Laporan Kue {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}"
            })
        ]
    });
});
</script>    
@endpush