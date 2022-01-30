<?php /** @var $product \App\Models\Product */ ?>

<div class="col-3 js-product-item product-item mb-3 text-center text-lg-left">

    <div class="mb-3 image position-relative">
        @if ($product->getSalePercentage())
            <span class="position-absolute text-white font-size-14 px-2" style="top: 0; right: 0; background: #D22020;">
                -{{ $product->getSalePercentage() }}%
            </span>
        @endif

        @include('partials.buttons.favorite', [
            'favoriteProductId' => $product->id,
            'favoriteState' => isset($product->favorite)
        ])

        <a href="{{ $product->getUrl() }}">
            <img
                src="{{ $product->getFirstMedia()->getUrl('catalog') }}"
                alt="{{ $product->extendedName() }}"
                class="img-fluid product-first-image"
            />
            <img
                src="{{ ($product->getMedia()->get(1) ?? $product->getFirstMedia())->getUrl('catalog') }}"
                alt="{{ $product->extendedName() }}"
                class="img-fluid product-second-image"
            />
        </a>
        <button
            type="button"
            aria-label="быстрый просмотр"
            data-src="{{ route('product.quick', $product->id) }}"
            class="quick-link btn btn-block btn-outline-dark d-none d-lg-block"
        >быстрый просмотр</button>
    </div>

    <b>{{ $product->simpleName() }}</b> <br>
    @if ($product->getPrice() < $product->getOldPrice())
        <span class="old_price">{!! $product->getFormattedOldPrice() !!}</span>
        <span class="new_price">{!! $product->getFormattedPrice() !!}</span>
    @else
        <span class="price">{!! $product->getFormattedPrice() !!}</span>
    @endif
    <br/>
    <span class="text-mutted">{{ $product->sizes->implode('name', ' | ') }}</span>
</div>
