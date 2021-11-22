@extends('admin.layout')

@section('title')
Dashboard
@endsection

@section('content')
<div id="appCapsule">

    <!-- Wallet Card -->
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <!-- Balance -->
            <div class="balance">
                <div class="left">
                    <span class="title">Pendapatan</span>
                    <h1 class="total">@currency($done)</h1>
                </div>
            </div>
            <!-- * Balance -->
            <!-- Wallet Footer -->
            {{-- <div class="wallet-footer">
                <div class="item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exchangeActionSheet">
                        <div style="width: 80%;font-size:15px" class="icon-wrapper bg-warning">
                            @currency($pending)
                        </div>
                        <strong>Transaksi Butuh Konfirmasi</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#withdrawActionSheet">
                        <div style="width: 80%;font-size:15px" class="icon-wrapper bg-danger">
                            @currency($cancel)
                        </div>
                        <strong>Transaksi Dibatalkan</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#sendActionSheet">
                        <div style="width: 80%;font-size:15px" class="icon-wrapper">
                            @currency($in_progress)
                        </div>
                        <strong>Transaksi Sedang Dikerjakan</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="app-cards.html">
                        <div style="width: 80%;font-size:15px" class="icon-wrapper bg-success">
                            @currency($done)
                        </div>
                        <strong>Transaksi Selesai</strong>
                    </a>
                </div>
            </div> --}}
            <!-- * Wallet Footer -->
        </div>
    </div>
    <!-- Wallet Card -->


    <!-- Dashboard -->
    <div class="section pt-1">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Transaksi Menunggu Konfirmasi
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard -->


</div>
@endsection