@extends('layout')

@section('content')
<div class="section-slide--default">
    <div class="owl-carousel" data-owl-auto="false" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on">
        <div class="ps-banner">
            <img class="mobile-only" src="img/banner1.png" alt="alt" />
            <img class="desktop-only" src="img/banner1.png" alt="alt" />
        </div>
    </div>
    <div class="section-slide__footer">
        <div class="container">
            <div class="row m-0">
                <div class="col-4">
                    <p><b>1000+ Produk Kue & Jajanan Terbaik</b></p>
                </div>
                <div class="col-4">
                    <p>50+ Unit Dagang yang tergabung</p>
                </div>
                <div class="col-4">
                    <p>Kualitas Pesanan Kue Terjamin 100%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ps-business--content">
    <div class="container">
        <div class="ps-business__service">
            <div class="ps-business__subtitle">unit dagang di kampung kue</div>
            <div class="ps-business__title">List Unit Dagang</div>
            <div class="owl-carousel ps-business__carousel" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="3" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="3" data-owl-duration="1000" data-owl-mousedrag="on">
                @foreach($merchants as $merchant)
                <div class="ps-business__item">
                    <a href="{{ route('merchant.detail', [$merchant->code]) }}">
                        @if($merchant->banner == "")
                            <img style="height:300px;width:100%;object-fit: cover;" src="{{ url('img/logo.png') }}" alt>
                        @else
                            <img style="height:300px;width:100%;object-fit: cover;" src="{{ url(\Config::get('website.url_photo').$merchant->banner) }}" alt>
                        @endif
                    </a>
                    <div class="ps-item__content">
                        <div class="ps-item__title">{{ $merchant->name }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="ps-business--download ps-business--content">
    <div class="container">
        <div class="ps-business__service" style="padding-top: 0;">
            <div class="ps-business__subtitle">Mengapa harus order di kampung kue</div>
            <div class="ps-business__title">Alasan Memilih Kami</div>
            <div class="row m-0">
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-users"></i></div>
                            <div class="ps-reason__number">01</div>
                        </div>
                        <div class="ps-reason__title">Berbasis Komunitas</div>
                        <p class="ps-reason__des">Dengan order di kampung kue, Anda telah membantu perekonomian masyarakat yang tergabung di kampung kue.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-star-half"></i></div>
                            <div class="ps-reason__number">02</div>
                        </div>
                        <div class="ps-reason__title">Kualitas Kue Terjamin</div>
                        <p class="ps-reason__des">Tidak perlu ragu dengan kualitas kue buatan para unit dagang di kampung kue. Karena kami memiliki standard mutu untuk tiap produk kue.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-thumbs-up2"></i></div>
                            <div class="ps-reason__number">03</div>
                        </div>
                        <div class="ps-reason__title">Pelayanan Terbaik</div>
                        <p class="ps-reason__des">Kami akan memberikan pelayanan terbaik dengan membuatkan kue sesuai waktu dan spesifikasi yang diinginkan.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-tags"></i></div>
                            <div class="ps-reason__number">04</div>
                        </div>
                        <div class="ps-reason__title">Harga Kue Terjangkau</div>
                        <p class="ps-reason__des">Kue dan makanan di kampung kue memiliki kualitas yang terbaik sehingga kue yang dijual disini harganya sangat terjangkau.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-cart"></i></div>
                            <div class="ps-reason__number">05</div>
                        </div>
                        <div class="ps-reason__title">Melayani Pre-order</div>
                        <p class="ps-reason__des">Kami melayani pre-order sesuai dengan waktu pesanan pembuatan masing-masing produk. Detailnya dapat dilihat di tiap produk.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 p-0">
                    <div class="ps-reason__box">
                        <div class="ps-reason__header">
                            <div class="ps-reason__icon"><i class="icon-license2"></i></div>
                            <div class="ps-reason__number">06</div>
                        </div>
                        <div class="ps-reason__title">Bahan Terbaik & Tanpa Pengawet</div>
                        <p class="ps-reason__des">Semua produk kami menggunakan bahan terbaik, berkualitas dan bebas dari berbagai macam pengawet makanan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="ps-business--product" style="background:none;">
    <div class="container">
        <div class="ps-business__subtitle">Yang paling banyak dicari di kampung kue</div>
        <div class="ps-business__title">Kategori Kue</div>
        <div class="ps-product__categorie">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="ps-product--business border">
                        <div class="ps-product__content"><a class="ps-product__name" href="shop-categories.html">Kue Bolu</a>
                            <div class="ps-product__item">23 items</div><a class="ps-product__link" href="shop-categories.html"><i class="icon-chevron-right-circle"></i></a>
                        </div>
                        <div class="ps-product__thumbnail"><a href="shop-categories.html"><img src="https://cdn.idntimes.com/content-images/community/2020/09/fromandroid-e93ddc0674ddb38b52548e3030a22de6_600x400.jpg" alt=""></a></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="ps-product--business border">
                        <div class="ps-product__content"><a class="ps-product__name" href="shop-categories.html">Roti Lapis</a>
                            <div class="ps-product__item">13 items</div><a class="ps-product__link" href="shop-categories.html"><i class="icon-chevron-right-circle"></i></a>
                        </div>
                        <div class="ps-product__thumbnail"><a href="shop-categories.html"><img src="https://craftlog.com/m/i/9165503=s1280=h960" alt=""></a></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="ps-product--business border">
                        <div class="ps-product__content"><a class="ps-product__name" href="shop-categories.html">Kue Ulang Tahun</a>
                            <div class="ps-product__item">34 items</div><a class="ps-product__link" href="shop-categories.html"><i class="icon-chevron-right-circle"></i></a>
                        </div>
                        <div class="ps-product__thumbnail"><a href="shop-categories.html"><img src="https://images.tokopedia.net/img/cache/700/product-1/2019/9/5/44503115/44503115_5b3e532d-1f13-472f-901f-66f595cf7124_1080_1080" alt=""></a></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="ps-product--business border">
                        <div class="ps-product__content"><a class="ps-product__name" href="shop-categories.html">Roti Brownies</a>
                            <div class="ps-product__item">34 items</div><a class="ps-product__link" href="shop-categories.html"><i class="icon-chevron-right-circle"></i></a>
                        </div>
                        <div class="ps-product__thumbnail"><a href="shop-categories.html"><img src="https://ngalam.co/wp-content/uploads/2018/08/2018_08_08_Lembutnya-Brownies-Panggang-di-Share-dBrownie-Malang.jpg" alt=""></a></div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="ps-product--business border">
                        <div class="ps-product__content"><a class="ps-product__name" href="shop-categories.html">Lumpia Basah</a>
                            <div class="ps-product__item">34 items</div><a class="ps-product__link" href="shop-categories.html"><i class="icon-chevron-right-circle"></i></a>
                        </div>
                        <div class="ps-product__thumbnail"><a href="shop-categories.html"><img src="https://img-global.cpcdn.com/recipes/6064e4b7dc762ec7/751x532cq70/lumpia-basah-foto-resep-utama.jpg" alt=""></a></div>
                    </div>
                </div>
                <div class="col-12 col-lg-4"><a class="ps-product__box" href="shop-categories.html">Lihat Semua Kategori <i class="icon-chevron-right-circle"></i></a></div>
            </div>
        </div>
    </div>
</div> --}}

<div class="ps-business--content ps-business--about">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="ps-about__subtitle">pelanggan</div>
                <div class="ps-about__title">Partner Kampung Kue</div>
            </div>
            <div class="col-12 col-lg-6">
                <p class="ps-about__des">Berikut ini adalah daftar pelanggan yang pernah order kue di Kampung Kue Surabaya</p>
                <div class="ps-about__text">Apabila ingin menjadi partner, silahkan hubungi nomor berikut <a href="#">(+62) 851-0022-0793</a></div>
            </div>
        </div>
        <div class="owl-carousel ps-business__carousel" data-owl-auto="true" data-owl-loop="true" data-owl-speed="8000" data-owl-gap="0" data-owl-nav="false" data-owl-dots="false" data-owl-item="5" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="5" data-owl-duration="1000" data-owl-mousedrag="on">
            <div class="ps-about__item" style="height:200px"><a><img style="width:50%" src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/City_of_Surabaya_Logo.svg/1200px-City_of_Surabaya_Logo.svg.png" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:50%;margin-top:10%" src="https://rekreartive.com/wp-content/uploads/2018/11/Logo-Poltekkes-Surabaya-Original-PNG-Terbaru-1140x1110.png" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:50%;margin-top:25%" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/2012_Citilink_Logo.svg/1200px-2012_Citilink_Logo.svg.png" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:100%;margin-top:10%" src="http://www.pahlawanekonomi.org/wp-content/uploads/2016/02/pe-logo-kecil-fix.png" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:100%;margin-top:10%" src="https://statik.tempo.co/?id=866798&width=1000" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:100%;margin-top:25%" src="{{ url('img\brand\perbanas.png') }}" alt></a></div>
            <div class="ps-about__item" style="height:200px"><a><img style="width:60%;margin-top:10%" src="{{ url('img\brand\diskop-jatim.png') }}" alt></a></div>
        </div>
    </div>
</div>
@endsection
