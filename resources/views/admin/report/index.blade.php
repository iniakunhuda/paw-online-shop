@extends('admin.layout')

@section('title')
    Pilih Laporan
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <div class="row">
            <div class="col-12 col-sm-12 mb-2">
                <h1 class="mt-3">Pilih Laporan</h1>
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.report.utama') }}">
                            <div class="card mt-5">
                                <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                    <h2 class="text-primary">
                                        Laporan Utama
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Laporan yang berisi daftar riwayat pesanan <br> mulai dari yang belum dibayar, sudah dibayar dan sudah dikirim
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.report.unitdagang') }}">
                            <div class="card mt-5">
                                <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                    <h2 class="text-primary">
                                        Laporan Unit Dagang
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Laporan yang berisi daftar penerimaan produsen <br> dan paguyuban dari order yang sudah selesai
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.report.kue') }}">
                            <div class="card mt-5">
                                <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                    <h2 class="text-primary">
                                        Laporan Kue Terfavorit
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Laporan yang berisi daftar kue yang paling diminati oleh pembeli
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.report.kategori') }}">
                            <div class="card mt-5">
                                <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                    <h2 class="text-primary">
                                        Laporan Per Kategori
                                    </h2>
                                    <p class="text-muted mb-0">
                                        Laporan yang berisi riwayat kategori mana yang memiliki pendapatan paling besar
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                    <a href="{{ route('admin.report.pesanan') }}">
                        <div class="card mt-5">
                            <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                <h2 class="text-primary">
                                    Laporan Pesanan
                                </h2>
                                <p class="text-muted mb-0">
                                    Laporan yang berisi daftar riwayat pesanan yang belum dibayar
                                </p>
                            </div>
                        </div>
                    </a>
                    </div>
                    <div class="col-6">
                    <a href="{{ route('admin.report.konsumen') }}">
                        <div class="card mt-5">
                            <div class="card-body text-center" style="padding-top:60px;padding-bottom:50px">
                                <h2 class="text-primary">
                                    Laporan Per Konsumen
                                </h2>
                                <p class="text-muted mb-0">
                                    Laporan yang berisi daftar riwayat pelanggan
                                </p>
                            </div>
                        </div>
                    </a>
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



@push('js')
<script>
$(document).ready( function () {
    $('table').DataTable();
});
</script>
@endpush
