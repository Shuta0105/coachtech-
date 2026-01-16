@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="product-list__content">
    <div class="product-list__header">
        <a href="#" data-tab=""
            class="product-list__header-tag"
            dusk="tab-recommend">
            おすすめ
        </a>
        <a href="#" data-tab="mylist"
            class="product-list__header-tag"
            dusk="tab-mylist">
            マイリスト
        </a>
    </div>
    <div class="product-list">
        <div id="item-list" class="product-list__inner" dusk="item-list">
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
            const params = new URLSearchParams(location.search);

            if (input.value) {
                params.set('keyword', input.value);
            } else {
                params.delete('keyword');
            }

            history.pushState(null, '', `${location.pathname}?${params.toString()}`);

            fetch(`${location.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    itemList.innerHTML = html;
                });

        }, 300);
    });

    document.querySelectorAll('[data-tab]').forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();

            const params = new URLSearchParams(location.search);
            const value = tab.dataset.tab;

            value ? params.set('tab', value) : params.delete('tab');

            history.pushState(null, '', `${location.pathname}?${params.toString()}`);

            fetch(`${location.pathname}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    itemList.innerHTML = html;
                });
            // タブ クリック後
            syncActiveTab();
        });
    });

    function syncActiveTab() {
        const params = new URLSearchParams(location.search);
        const currentTab = params.get('tab');

        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.classList.toggle(
                'active',
                tab.dataset.tab === (currentTab ?? '')
            );
        });
    }

    document.addEventListener('DOMContentLoaded', syncActiveTab);
</script>
@endsection