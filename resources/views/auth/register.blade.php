@extends('layout', ['show_footer' => false])

@section('content')
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="ps-breadcrumb__list">
            <li class="active"><a href="{{ url('/') }}">Home</a></li>
            <li><a href="javascript:void(0);">Register</a></li>
        </ul>
    </div>
</div>
<section class="section--login">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="login__box">
                    <div class="login__header">
                        <h3 class="login__register"><a href="{{ url('login') }}">MASUK</a></h3>
                        <h3 class="login__login"><a href="{{ url('register') }}">DAFTAR</a></h3>
                    </div>
                    <div class="login__content">
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <strong class="login__label">Buat Akun baru untuk menggunakan sistem Kampung Kue</strong><br><br>

                            <label>Nama Lengkap</label>
                            <div class="input-group">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                            @error('name')
                                <div class="alert alert-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <label>Whatsapp</label>
                            <div class="input-group">
                                <input id="whatsapp" type="text" class="form-control @error('whatsapp') is-invalid @enderror" name="whatsapp" value="{{ old('whatsapp') }}" required autocomplete="whatsapp" autofocus>
                            </div>
                            @error('whatsapp')
                                <div class="alert alert-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror


                            <label>Email</label>
                            <div class="input-group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            @error('email')
                                <div class="alert alert-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <label>Password</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            </div>
                            @error('password')
                                <div class="alert alert-danger" role="alert">
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <label>Konfirmasi Password</label>
                            <div class="input-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <div class="input-group mb-0">
                                <button type="submit" class="btn btn-login">
                                    DAFTAR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h3 class="login__title">Daftar untuk menggunakan website Kampung Kue</h3>
                <p class="login__description">Kampung Kue Surabaya merupakan sebuah komunitas yang bergerak dibidang kue di daerah Rungkut, Kota Surabaya</p>
                <div class="login__orther">
                    <p> <i class="icon-truck"></i>Order Kue Cepat & Berkualitas</p>
                    <p> <i class="icon-alarm2"></i>Pilih order kapan saja</p>
                    <p><i class="icon-star"></i>Banyak pilihan toko kue terbaik</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
