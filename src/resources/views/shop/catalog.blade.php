@extends('layouts.app')

@section('title', "Купить {$categoryTitle} с примеркой по Беларуси")

@section('breadcrumbs', Breadcrumbs::render('category', $category))

@section('content')
    <div class="row d-flex d-lg-none mb-2">
        <div class="col-6 align-self-center">
            <button class="btn btn-outline-dark rounded" type="button" data-toggle="collapse" data-target="#sidebarFilters">
                Фильтр
            </button>
            <span class="text-muted font-size-12">
                {{ DeclensionNoun::make($productsTotal, 'модель') }}
            </span>
        </div>
        <select onchange="window.location.href = this.value" class="form-control col-6">
            @foreach ($sortingList as $key => $value)
                <option value="{{ URL::current() . "?sort=$key" }}" {{ $sort == $key ? 'selected' : null }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="collapse col-12 col-lg-3 col-xl-2 d-lg-block filters-sidebar" id="sidebarFilters">
        @include('shop.filters.all')
    </div>
    <div class="col-12 col-lg-9 col-xl-10 catalog-page">

        {{ Banner::getCatalogTop() }}

        <div class="col-12 my-4 d-none d-lg-block">
            <div class="row justify-content-end align-items-center">

                {{ Currency::getSwitcher() }}

                <div class="col-auto align-self-center ml-3 mr-2">
                    Сортировка:
                </div>
                <select onchange="window.location.href = this.value" class="form-control col-4 col-lg-3 col-xl-2">
                    @foreach ($sortingList as $key => $value)
                        <option value="{{ URL::current() . "?sort=$key" }}" {{ $sort == $key ? 'selected' : null }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 scrolling-pagination px-0">
            <input type="hidden" name="cursor" value="{{{ optional($products->nextCursor())->encode() }}}">
            <input type="hidden" name="has_more" value="@json($products->hasMorePages())">
            <div class="row justify-content-start" id="catalog-endless-scroll">
                @forelse($products as $product)
                    @include('shop.catalog-product', compact('product'))
                @empty
                    <p>Нет товаров</p>
                @endforelse
                {{-- {{ $products->links() }} --}}
            </div>
        </div>

    </div>

@endsection
