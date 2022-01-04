@extends('layouts.app')

@section('title', 'Корзина')

@section('breadcrumbs', Breadcrumbs::render('cart'))

@section('content')

<div class="col-12 col-md-4 col-xl-5 my-5 text-center text-muted">
    <p>
        Ваш заказ принят!
    </p>
    <h3 class="text-danger">Заказ №{{ $order->id }}</h3>
    <p>
        от {{ date('j F') }} 2021 на сумму
        {!! Currency::format($order->getTotalPrice(), $order->currency) !!}
    </p>
    <div class="row px-5 py-4 text-left" style="background: #FBFBFD">

        <div class="col-6 py-2 font-weight-bold">
            Статус заказа
        </div>
        <div class="col-6 py-2">
            Ожидает подтверждения менеджером
        </div>

        <div class="col-6 py-2 font-weight-bold">
            Способ получения
        </div>
        <div class="col-6 py-2">
            @if (!empty($order->delivery))
                {{ $order->delivery->name }} <br>
            @endif
            {{ $order->user_addr }}
        </div>

        @if (!empty($order->payment))
            <div class="col-6 py-2 font-weight-bold">
                Способ оплаты
            </div>
            <div class="col-6 py-2">
                {{ $order->payment->name }}
            </div>
        @endif

    </div>

    <p class="font-weight-light font-size-12 text-center mt-2">
        По указанному номеру с Вами свяжется менеджер для <br>
        подтверждения условий доставки
    </p>
</div>

<div class="col-12 my-5 text-center">
    <h3 class="font-weight-light">
        Специально для вас / Недавно просмотренные
    </h3>
    <div class="row justify-content-center align-items-end">
        @foreach ($recomended as $product)
            <div class="col-12 col-md-auto js-product-item product-item mb-3 px-3 text-left">
                <a href="{{ $product->category->getUrl() . '/' . $product->slug }}">
                    <p>
                        <img src="{{ $product->getFirstMedia()->getUrl('catalog') }}" alt="{{ $product->title }}"
                                    class="img-fluid" style="max-width: 180px">
                    </p>
                </a>
                <b>{{ $product->brand->name }} {{ $product->id }}</b> <br>
                <span class="text-mutted">{{ $product->category->title }}</span> <br>
                @if ($product->price < $product->old_price)
                    <span class="old_price">{!! $product->getFormattedOldPrice() !!}</span>
                    <span class="new_price">{!! $product->getFormattedPrice() !!}</span>
                @else
                    <span class="price">{!! $product->getFormattedPrice() !!}</span>
                @endif

            </div>
        @endforeach
    </div>
    <div class="col-12 my-5 text-center">
        <a href="{{ route('shop') }}" class="btn btn-dark px-4">
            Продолжить покупки
        </a>
    </div>
</div>

@endsection
