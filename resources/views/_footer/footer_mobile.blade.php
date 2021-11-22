
    <div class="ps-footer-mobile">
        <div class="menu__content">
            <ul class="menu--footer">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}"><i class="icon-home3"></i><span>Home</span></a></li>
                <li class="nav-item"><a class="nav-link footer-category" href="{{ route('product.index') }}"><i class="icon-list"></i><span>Produk</span></a></li>
                @if(\Auth::check())
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="icon-user"></i><span>Dashboard</span></a></li>
                @else
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="icon-user"></i><span>Login</span></a></li>
                @endif
            </ul>
        </div>
    </div>