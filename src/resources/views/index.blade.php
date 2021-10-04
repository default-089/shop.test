@extends('layouts.app')

@section('title', 'Barocco | интернет-магазин модной обуви и кожгалантерии')

@section('content')

{{ Banner::getIndexMain() }}

<div class="col-md-12">

    @forelse ($productCarousels as $productCarousel)

        @if ($loop->index == 1 || ($loop->last && $loop->index <= 1))
            <hr class="d-none d-sm-block my-4">
            @include('includes.advantages-block')
            <hr class="d-none d-sm-block my-4">
        @endif

        <h4 class="text-center mt-3">{{ $productCarousel['title'] }}</h4>
        <div class="js-product-carousel" data-slick='{
            "slidesToShow": 5,
            "slidesToScroll": 5,
            "autoplay": true,
            "responsive": [
                {
                    "breakpoint": 1305,
                    "settings": {
                        "slidesToShow": 4,
                        "slidesToScroll": 4
                    }
                },
                {
                    "breakpoint": 830,
                    "settings": {
                        "slidesToShow": 1,
                        "slidesToScroll": 1
                    }
                }
            ]
        }'>
            @foreach ($productCarousel['products'] as $product)
                <div class="col position-relative">
                    <a href="{{ $product->getUrl() }}">
                        @if ($product->getSalePercentage())
                            <span class="position-absolute text-white font-size-14 px-2" style="top: 0; right: 10px; background: #D22020;">
                                -{{ $product->getSalePercentage() }}%
                            </span>
                        @endif
                        <img
                            src="{{ $product->getFirstMedia()->getUrl('catalog') }}"
                            alt="{{ $product->title }}"
                            class="img-fluid product-first-image"
                        >
                        <span>{{ $product->getFullName() }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    @empty
        <hr class="d-none d-sm-block my-4">
        @include('includes.advantages-block')
        <hr class="d-none d-sm-block my-4">
    @endforelse

    <div class="col-12 my-5">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto text-center">
                <h1 class="display-4">
                    Наш инстаграм
                    <a href="{{ config('contacts.instagram.link') }}">@barocco.by</a>
                </h1>
            </div>
            <div class="col-12 col-sm-auto text-center ml-auto">
                <a class="btn btn-dark" href="{{ config('contacts.instagram.link') }}">Подпишись</a>
            </div>
        </div>
        <div class="row mx-n2 js-instagram-posts">
            @foreach ($instagramPosts as $post)
                <div class="col-12 col-sm-4">
                    <a href="{{ $post['url'] }}" rel="noopener" target="_blank">
                        <img src="{{ $post['image'] }}" alt="" class="img-fluid" />
                    </a>
                    <div class="row">
                        <div class="col-auto">@barocco</div>
                        <div class="col-auto ml-auto">
                            &#10084;&nbsp;{{ $post['likes'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- <div class="row mt-4 mb-5">
            <div class="col text-center">
                <a href="{{ config('contacts.instagram.link') }}">Больше образов</a>
            </div>
        </div> --}}
    </div>


</div>

{{ Banner::getIndexBottom() }}

{{-- wrapper close --}}
</div>
<div class="row my-5">
    <div class="col-12 bg-danger py-5">
        <div class="row wrapper">
            <div class="col-12 col-md-6 text-center text-md-left">
                <h1 class="display-4">BAROCCO club</h1>
                <p class="font-size-18">Зарегистрируйся в программе лояльности и получи приветственный бонус</p>
            </div>
            <div class="col-12 col-md-6 mt-4 mt-md-0">
                <div class="row justify-content-center align-items-center h-100">
                    <a href="{{ route('dashboard-card') }}" class="btn btn-white col-10 col-lg-8 col-xl-6 p-2">
                        Присоединиться
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row wrapper justify-content-center">
{{-- wrapper open --}}


<div class="col-12 text-justify my-5">
    BAROCCO.BY - ведущий интернет магазин по продаже обуви из натуральной кожи и замши в Беларуси.<br>
    В нашем интернет магазине представлены только качественные модели обуви, которые отвечают современным тенденциям моды.<br>
    <ul>
        <li>Мы работаем с 2015 года</li>
        <li>Гарантия на всю продукцию</li>
        <li>Широкий размерный ряд</li>
        <li>100% оригинальные бренды</li>
    </ul>
    <br>
    Мы создали свой бренд обуви BAROCCO STYLE. В наших изделиях используются только натуральные материалы, такие как кожа, мех и замша.<br>
    Мы производим женские коллекции ОБУВИ согласно последним веяниям моды.<br>
    Наша обувь идеальна в носке и  подойдёт каждому, так как она подобрана с учетом особенностей строения женской ступни.<br>
    <br>
    Также BAROCCO.BY является официальным поставщиком именитых обувных брендов VITACCI, Basconi, Sasha Fabiani. Благодаря многолетнему сотрудничеству с производителями у нас лучшие цены в Беларуси.<br>
    <br>
    Мы предлагаем широкий ассортимент обуви для каждого сезона и случая:<br>
    <ul>
        <li>для лета босоножки, сандалии и сабо</li>
        <li>для демисезона и зимы: ботильоны, ботинки, сапоги и ботфорты</li>
        <li>спортивные и повседневные кроссовки, слипоны и кеды</li>
        <li>офисные туфли и лоферы</li>
        <li>вечерние модели туфель, ботильон и босоножек для юбилеев, свадеб, свиданий и др. торжественных случаев</li>
    </ul>
    <br>
    Барокко бай - это не только ассортимент, но и сервис.<br>
    Для Беларуси доступна рассрочка, курьерская доставка и примерка.<br>
    В Россию возможна доставка до отделения СДЭК или EMS<br>
    <br>
    У нас можно купить обувь из натуральных материалов по приемлемым ценам.<br>
    Покупайте качественную обувь - быстро и надежно с BAROCCO.BY!
</div>
@endsection
