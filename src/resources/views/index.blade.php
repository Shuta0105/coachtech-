@php use Illuminate\Support\Str; @endphp
@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="product-list__content">
    <div class="product-list__header">
        <a href="/?keyword={{ request('keyword') }}" class="product-list__header-tag {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist&keyword={{ request('keyword') }}" class="product-list__header-tag {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <div class="product-list">
        <div id="item-list" class="product-list__inner">
            @include('search-list', ['items' => $items])
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const input = document.getElementById('search-input')
    const itemList = document.getElementById('item-list');

    let timer = null;

    input.addEventListener('input', () => {
        clearTimeout(timer);

        timer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);
            if (input.value) {
                params.set('keyword', input.value);
            } else {
                params.delete('keyword');
            }

            history.replaceState(null, '', `?${params.toString()}`);

            fetch(`/items/search?${params.toString()}`)
                .then(res => res.text())
                .then(html => {
                    itemList.innerHTML = html;
                });
        }, 300);
    });

    if (input.value) {
        fetchItems();
    }
</script>
@endsection