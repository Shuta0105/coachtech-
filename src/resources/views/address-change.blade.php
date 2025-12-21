@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address-change.css') }}">
@endsection

@section('content')
<div class="address-change__content">
    <div class="address-change__header">
        <h1>住所の変更</h1>
    </div>
    <form action="/purchase/address/{{ $item_id }}" class="address-change__form" method="post">
        @csrf
        <label class="form__label">郵便番号</label>
        <input class="form__input--text" type="text" name="post_code" value="{{ old('post_code') }}">
        @error ('post_code')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">住所</label>
        <input class="form__input--text" type="text" name="address" value="{{ old('address') }}">
        @error ('address')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">建物名</label>
        <input class="form__input--text" type="text" name="building" value="{{ old('building') }}">
        <div class="form__button">
            <button class="form__button--submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection