@extends('layouts.app')

@section('title', 'Отзывы')

@section('breadcrumbs', Breadcrumbs::render('feedbacks'))

@section('content')

<div class="col-12">

    <div class="row justify-content-between mb-3">
        <div class="col-auto align-self-center d-none d-md-block">
            <h2>ОЦЕНКИ И ОТЗЫВЫ</h2>
        </div>
        <div class="col-12 col-md-auto">
            <button type="button" class="btn btn-danger btn-block py-3" data-fancybox data-src="#leave-feedback-modal">
                ОСТАВИТЬ ОТЗЫВ О СВОЕЙ ПОКУПКЕ
            </button>
        </div>
    </div>

    <div class="row justify-content-between px-5">
        @foreach ($feedbacks as $feedback)
            <div class="col-12 item my-4">
                <div class="row">
                    <div class="col-3">
                        {{-- @if ($feedback->rating > 0)
                            <ul>
                                @for ($i = 1; $i <= 5; $i++)
                                    <li>
                                        <label class="check">
                                            <input type="checkbox" {{ $feedback->rating >= $i ? 'checked' : '' }}>
                                            <i class="checkmark icon ic-star"></i>
                                        </label>
                                    </li>
                                @endfor
                            </ul>
                        @endif --}}
                        <b>{{ $feedback->user_name }}</b>
                    </div>
                    <div class="col-7">
                        <p>{{ $feedback->text }}</p>
                        @foreach ($feedback->getMedia() as $image)
                            <a href="{{ $image->getUrl('full') }}" data-fancybox="images">
                                <img src="{{ $image->getUrl('thumb') }}" class="img-fluid">
                            </a>
                        @endforeach
                    </div>
                    <div class="col-2 text-center">
                        <span>{{ $feedback->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
                {{-- {{ dump($feedback) }} --}}
            </div>
        @endforeach

    </div>

    <div class="row justify-content-center justify-content-md-end mb-5">
        <div class="col-md-auto">
            {{ $feedbacks->links() }}
        </div>
    </div>

</div>


<div id="leave-feedback-modal">
    <form id="leave-feedback" action="{{ route('feedbacks.store') }}" method="post">
        @csrf
        <h3 class="mb-4">Оставить отзыв</h3>

        {{-- <div class="row form-group">
            <div class="col-12 col-md-4">
                <b>Оцените товар</b>
            </div>
            <div class="col-12 col-md-8">
                звезды
            </div>
        </div> --}}

        <div class="row form-group">
            <label for="inputName" class="col-12 col-md-4 col-form-label">
                <b>Оставьте комментарий</b>
            </label>
            <div class="col-12 col-md-8">
                <textarea rows="5" class="form-control" name="text" id="textareaText" placeholder="Что вам понравилось в этом товаре?"></textarea>
            </div>
        </div>

        <div class="row form-group">
            <label for="inputName" class="col-12 col-md-4 col-form-label">
                <b>Представьтесь, пожалуйста</b>
            </label>
            <div class="col-12 col-md-8">
                <input type="text" name="name" id="inputName" class="form-control" placeholder="Имя" required>
            </div>
        </div>

        <div class="row form-group">
            <label for="inputMedia" class="col-12 col-md-4 col-form-label">
                <b>Загрузите фотографии</b>
            </label>
            <div class="col-12 col-md-8">
                <input type="file"  accept="image/*, video/*" name="media" id="inputMedia" class="form-control-file" multiple>
            </div>
        </div>

        <div class="row form-group justify-content-end">
            <button type="button" class="js-leave-feedback-btn btn btn-dark px-4">Оставить отзыв</button>
        </div>

    </form>
</div>



@endsection
