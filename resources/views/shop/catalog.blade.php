@extends('layouts.app')

@section('title', 'Женская обувь')

@section('breadcrumbs', Breadcrumbs::render('category', $currentCategory))

@section('content')
    <div class="col-3 col-xl-2 d-none d-lg-block filters-sidebar">
        @include('shop.filters.all')
    </div>
    <div class="col-12 col-lg-9 col-xl-10 catalog-page">

        {{ Banner::getCatalogTop() }}

        <div class="col-12 my-4">
            <div class="row justify-content-end">
                <div class="col-auto align-self-center mr-3 d-none d-md-block">
                    Сортировка:
                </div>
                <select name="sorting" class="form-control col-6 col-md-4 col-lg-3 col-xl-2">
                    <option data-href="{{ URL::current() . '?' . http_build_query(['sort' => 'rating']) }}"
                        {{ $sort == 'rating' ? 'selected' : null }}>
                        по популярности
                    </option>
                    <option data-href="{{ URL::current() . '?' . http_build_query(['sort' => 'newness']) }}"
                        {{ $sort == 'newness' ? 'selected' : null }}>
                        новинки
                    </option>
                    <option data-href="{{ URL::current() . '?' . http_build_query(['sort' => 'price-up']) }}"
                        {{ $sort == 'price-up' ? 'selected' : null }}>
                        по возрастанию цены
                    </option>
                    <option data-href="{{ URL::current() . '?' . http_build_query(['sort' => 'price-down']) }}"
                        {{ $sort == 'price-down' ? 'selected' : null }}>
                        по убыванию цены
                    </option>
                </select>
            </div>
        </div>

        <div class="col-12 scrolling-pagination">
            <div class="row jscroll-inner justify-content-between">
                @forelse($products as $product)
                    @php /** @var App\Product $product */ @endphp
                    <div class="col-12 col-md-auto js-product-item product-item mb-3 px-3">
                        <a href="{{ $product->category->getUrl() . '/' . $product->slug }}">
                            <p>
                                <img src="{{ $product->getFirstMedia()->getUrl('catalog') }}" alt="{{ $product->title }}"
                                    class="img-fluid">
                            </p>
                        </a>
                        <b>{{ $product->getFullName() }}</b> <br>
                        @if ($product->price < $product->old_price)
                            <s>{{ round($product->old_price, 2) }} руб.</s>
                            <font color="#D22020">{{ round($product->price, 2) }} руб.</font><br>
                        @else
                            {{ round($product->price, 2) }} руб.<br>
                        @endif
                        {{-- <p><b>Категория: </b>{{ $product->category->title }}</p> --}}
                        {{-- <p><b>Размеры: </b>{{ $product->sizes->implode('name', ',') }}</p> --}}
                        <span class="text-mutted">{{ $product->sizes->implode('name', ' | ') }}</span>

                        {{-- <p><b>Цвет: </b>{{ $product->color->name ?? '' }}</p> --}}
                        {{-- <p><b>Материал: </b>{{ $product->fabrics->implode('name', ',') }}</p> --}}
                        {{-- <p><b>Каблук: </b>{{ $product->heels->implode('name', ',') }}</p> --}}
                        {{-- <p><b>Стиль: </b>{{ $product->styles->implode('name', ',') }}</p> --}}
                        {{-- <p><b>Сезон: </b>{{ $product->season->name }}</p> --}}
                        {{-- <p><b>Теги: </b>{{ $product->tags->implode('name', ',') }}</p> --}}
                        {{-- <p><b>Бренд: </b>{{ $product->brand->name }}</p> --}}
                    </div>
                @empty
                    <p>Нет товаров</p>
                @endforelse
                {{ $products->links() }}
            </div>
        </div>

    </div>

@endsection
