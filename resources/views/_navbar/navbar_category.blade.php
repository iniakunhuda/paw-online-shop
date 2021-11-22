@php
    $header_cats = \App\Models\Category::orderBy('name', 'ASC')->get()->toArray();
    $_CATEGORIES = [];

    foreach((array) $header_cats as $ct) {
        if($ct['id_parent'] == null) {
            $ct['childs'] = [];
            $_CATEGORIES[$ct['_id']] = $ct;
        }
    }

    foreach((array) $header_cats as $ct) {
        if($ct['id_parent'] != null) {
            $_CATEGORIES[$ct['id_parent']]['childs'][] = $ct;
        }
    }
@endphp

<ul class="list">
    <li onclick="changeCategoryNavbar('Semua Kategori')" class="category-option">
        <a href="javascript:void(0);">
            Semua Kategori
        </a>
    </li>
    @if(count($_CATEGORIES) > 0)
        @foreach($_CATEGORIES as $ct)
        <li onclick="changeCategoryNavbar('{{ $ct['name'] }}')" class="category-option">
            <a href="javascript:void(0);">
            {{ $ct['name'] }}
            </a>
        </li>
            @if(count($ct['childs']) > 0)
                @foreach($ct['childs'] as $cd)
                <li onclick="changeCategoryNavbar('{{ $cd['name'] }}')" class="category-option" style="padding-left:20px">
                    <a href="javascript:void(0);">
                        {{ $cd['name'] }}
                    </a>
                </li>
                @endforeach
            @endif
        @endforeach
    @endif
    
    {{-- <li class="category-option"><a>Fresh</a><span class="sub-toggle"><i class="icon-chevron-down"></i></span>
        <ul>
            <li> <a href="#">Meat & Poultry</a>
            </li>
            <li> <a href="#">Fruit</a>
            </li>
            <li> <a href="#">Vegetables</a>
            </li>
            <li> <a href="#">Milks, Butter & Eggs</a>
            </li>
            <li> <a href="#">Fish</a>
            </li>
            <li> <a href="#">Frozen</a>
            </li>
            <li> <a href="#">Cheese</a>
            </li>
            <li> <a href="#">Pasta & Sauce</a>
            </li>
        </ul>
    </li> --}}
</ul>

@push('js')
<script>
    function changeCategoryNavbar(val) {
        $('#topbar_search_category_value').val(val);
        $('#topbar_search_category_label').text(val);
    }
    @if(isset($_GET['category']))
        $('#topbar_search_category_value').val('{{ $_GET["category"] }}');
        $('#topbar_search_category_label').text('{{ $_GET["category"] }}');
    @endif
</script>
@endpush