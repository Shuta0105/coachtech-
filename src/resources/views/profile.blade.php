@php use Illuminate\Support\Str; @endphp
@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile__content">
    <div class="profile__user">
        <div class="profile__user-icon">
            <img src="{{ $user_profile->avatar ? asset('storage/' . $user_profile->avatar) : asset('img/kkrn_icon_user_6.png') }}">
        </div>
        <div class="profile__user-name">
            <h1>{{ $user_profile->user->name }}</h1>
        </div>
        <div class="profile__user-button">
            <a href="/mypage/profile" class="profile__user-button--edit">プロフィールを編集</a>
        </div>
    </div>

    <div class="product-list">
        <div class="product-list__header">
            <a href="/mypage?page=sell" class="product-list__header-tag {{ request('page') === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="/mypage?page=buy" class="product-list__header-tag {{ request('page') === 'buy' ? 'active' : '' }}">購入した商品</a>
        </div>
        <div class="product-list__content">
            <div class="product-list__inner">
                @foreach ($items as $item)
                <div class="product-list__item">
                    <div class="product-list__img">
                        @if ($item->order_count > 0)
                        <span class="product-list__item-sold">Sold</span>
                        @endif
                        <img src="{{ Str::startsWith($item->img, 'http') ? $item->img : asset('storage/' . $item->img) }}">
                    </div>
                    <div class="product-list__item-name">{{ $item->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection