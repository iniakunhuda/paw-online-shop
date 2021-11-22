@extends('admin.layout')

@section('title')
    Detail Transaksi
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-2">
        <a href="{{ route('admin.trans.index') }}" class="btn btn-default mb-2">Kembali</a>
        <div class="row">
            <div class="col-12 col-sm-6 mb-2">
                <div class="card">
                    <div class="card-header">
                        Detail Transaksi
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Kode</label>
                                    <p class="mb-0 mt-0">{{ $trans->invno }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group boxed">
                                        <div class="input-wrapper">
                                            <label class="label">Tanggal Order</label>
                                            <p class="mb-0 mt-0">{{ \Carbon\Carbon::parse($trans->date)->isoFormat('dddd, D MMMM Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group boxed">
                                        <div class="input-wrapper">
                                            <label class="label">Tanggal Rencana Kirim</label>
                                            @if($trans->delivery_time != "")
                                            <p class="mb-0 mt-0">
                                                {{ \Carbon\Carbon::parse($trans->delivery_time)->isoFormat('dddd, D MMMM Y') }}
                                                {{ \Carbon\Carbon::createFromTimestamp($trans->delivery_time)->format('H:i') }}
                                            </p>
                                            @else
                                            <p class="mb-0 mt-0">-</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Total Bayar</label>
                                    <p class="mb-0 mt-0">@currency($trans->price['total'])</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Bank</label>
                                    <p class="mb-0 mt-0">{{ $trans->payment }}</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Status</label>
                                    <p class="mb-0 mt-0">
                                        @php
                                            switch ($trans->status) {
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
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 mb-2">
                <div class="card">
                    <div class="card-header">
                        Detail Pembeli
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Nama Lengkap</label>
                                    <p class="mb-0 mt-0">{{ $trans->buyer['name'] }}</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Jenis Pengiriman</label>
                                    <p class="mb-0 mt-0">
                                        {{ ($trans->shipping['method'] == "ambil_di_tempat") ? 'Ambil di Tempat' : 'Dengan Jasa Pengiriman' }}
                                    </p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Alamat</label>
                                    <p class="mb-0 mt-0">{{ $trans->buyer['address'] }}</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Whatsapp</label>
                                    <p class="mb-0 mt-0">{{ $trans->buyer['whatsapp'] }}</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Provinsi</label>
                                    <p class="mb-0 mt-0">{{ $trans->buyer['province'] }}</p>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Kota</label>
                                    <p class="mb-0 mt-0">{{ $trans->buyer['city'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header">
                        Detail Produk
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach((array) $trans->products as $prod)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-12 col-sm-2">
                                                <img class="img-fluid img-responsive" src="{{ url(\Config::get('website.url_photo').$prod['pict']) }}" alt="Foto">
                                            </div>
                                            <div class="col-12 col-sm-10">
                                                <h4>{{ $prod->name }}</h4>
                                                <small class="text-muted">Dijual Oleh {{ $_merchants[$prod->merchant]['name'] ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:200px">@currency($prod->price)</td>
                                    <td style="width:100px">{{ $prod->qty }}</td>
                                    <td style="width:200px">@currency($prod->subtotal)</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6">
                &nbsp;
            </div>
            <div class="col-12 col-sm-6 mb-2">
                <div class="card">
                    <div class="card-header">
                        Ubah Status Transaksi
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.trans.update', [$trans->_id]) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="label">Pilih Status</label>
                                    <select name="status" class="form-control">
                                        <option value="pending" {{ ($trans->status == 'pending') ? 'selected' : '' }}>
                                            Menunggu konfirmasi Admin
                                        </option>
                                        <option value="in_progress" {{ ($trans->status == 'in_progress') ? 'selected' : '' }}>
                                            Pesanan sedang diproses oleh Admin
                                        </option>
                                        <option value="done" {{ ($trans->status == 'done') ? 'selected' : '' }}>
                                            Pesanan selesai
                                        </option>
                                        <option value="cancel" {{ ($trans->status == 'cancel') ? 'selected' : '' }}>
                                            Pesanan dibatalkan
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group boxed">
                                <label class="label">Catatan</label>
                                <textarea cols="20" class="form-control" rows="5" name="notes">{{ $trans->notes[0] ?? '' }}</textarea>
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