@php use Illuminate\Support\Str; @endphp
@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
<div class="product-detail__content">
    <div class="product-detail__img">
        <img src="{{ Str::startsWith($item->img, 'http') ? $item->img : asset('storage/' . $item->img) }}">
    </div>
    <div class="product-detail__text">
        <div class="product-detail__header">
            <h1>{{ $item->name }}</h1>
        </div>
        <div class="product-detail__brand">
            <p>{{ $item->brand }}</p>
        </div>
        <div class="product-detail__price">￥{{ $item->price }}(税込)</div>
        <div class="product-detail__icons">
            <!-- いいね -->
            <div class="product-detail__icon-box">
                <img
                    class="product-detail__icon--heart {{ $item->likedby(auth()->user()) ? 'active' : '' }}"
                    src="{{ $item->likedBy(auth()->user()) 
                        ? asset('img/ハートロゴ_ピンク.png') 
                        : asset('img/ハートロゴ_デフォルト.png') }}"
                    data-active="{{ asset('img/ハートロゴ_ピンク.png') }}"
                    data-default="{{ asset('img/ハートロゴ_デフォルト.png') }}"
                    data-id="{{ $item->id }}">
                <span class="product-detail__count js-like-count">{{ $item->likes_count }}</span>
            </div>
            <!-- コメント -->
            <div class="product-detail__icon-box">
                <img class="product-detail__icon--comment"
                    src="{{ asset('img/ふきだしロゴ.png') }}">
                <span class="product-detail__count">{{ $commentCount }}</span>
            </div>
        </div>
        <div class="product-detail__button">
            <a href="/purchase/{{ $item->id }}" class="product-detail__button--purchase">購入手続きへ</a>
        </div>
        <div class="product-detail__desc">
            <h2>商品説明</h2>
        </div>
        <div class="product-detail__dex--text">
            <p>{{ $item->detail }}</p>
        </div>
        <!-- <div class="product-detail__desc--color">
            <p>カラー：グレー</p>
        </div>
        <div class="product-detail__desc--condition">
            <p>新品<br>商品の状態は良好です。傷もありません。</p>
        </div> -->
        <div class="product-detail__desc--shipping">
            <p>購入後、即発送いたします。</p>
        </div>
        <div class="product-detail__info">
            <h2>商品の情報</h2>
        </div>
        <div class="product-detail__table">
            <table class="product-detail__table-inner">
                <tr class="product-detail__table-row">
                    <th class="product-detail__table-header">カテゴリー</th>
                    @foreach ($item_categories as $category)
                    <td class="product-detail__table-item">{{ $category->category->content }}</td>
                    @endforeach
                </tr>
                <tr class="product-detail__table-row">
                    <th class="product-detail__table-header">商品の状態</th>
                    <td class="product-detail__table-item">{{ $item->condition->content }}</td>
                </tr>
            </table>
        </div>
        <div class="product-detail__comment-header">
            <h2>コメント({{ $commentCount }})</h2>
        </div>
        <div class="product-detail__comments">
            @foreach ($comments as $comment)
            <div class="product-detail__comment">
                <div class="product-detail__comment-header">
                    <img src="" alt="">
                    <span class="product-detail__comment-header--name">{{ $comment->user->name }}</span>
                </div>
                <div class="product-detail__comment-body">{{ $comment->content }}</div>
            </div>
            @endforeach
        </div>
        <div class="product-detail__put-comment">
            <h3>商品へのコメント</h3>
        </div>
        <form action="/comment/{{ $item->id }}" method="post">
            @csrf
            <textarea class="product-detail__input--textarea" name="comment" rows="10"></textarea>
            @error('comment')
            <div class="form__error">
                {{ $message }}
            </div>
            @enderror
            <div class="product-detail__button">
                <button class="product-detail__button--comment">コメントを送信する</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    const heart = document.querySelector('.product-detail__icon--heart');
    const token = document.querySelector('meta[name="csrf-token"]').content;
    heart.addEventListener('click', () => {
        const itemId = heart.dataset.id;
        const countEl = document.querySelector('.js-like-count');

        fetch(`/like/${itemId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                },
            })
            .then(res => res.json())
            .then(data => {
                countEl.textContent = data.likes_count;
                heart.src = data.liked ?
                    heart.dataset.active :
                    heart.dataset.default;

                heart.classList.toggle('active', data.liked);
            })
    });
</script>
@endsection