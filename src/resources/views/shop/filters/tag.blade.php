<div class="filter-block tags">
    <div class="title"><span>ТЕГИ</span></div>
    <div class="list" {{-- style="display: none" --}}>
        <ul>
            @foreach ($filters['tags'] as $slug => $filter)
                <li class="check {{ isset($currentFilters[$filter['model']][$slug]) ? 'checked' : null }}">
                    <a href="{{ isset($currentFilters[$filter['model']][$slug]) ? UrlHelper::generate([], [$filter]) : UrlHelper::generate([$filter]) }}">
                        <span>{{ $filter['name'] }}</span>
                        <i class="checkmark"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
