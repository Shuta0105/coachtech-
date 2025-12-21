@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell__content">
    <div class="sell__header">
        <h1>商品の出荷</h1>
    </div>
    <form action="/sell" class="sell__form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form__header--secondary">
            <h3>商品画像</h3>
        </div>
        <div class="form__img">
            <img class="form__img-preview" src="">
            <input id="file" name="img" type="file" hidden>
            <label class="form__img-button" for="file">画像を選択する</label>
        </div>
        @error ('img')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__header--primary">
            <h2>商品の詳細</h2>
        </div>
        <div class="form__header--secondary">
            <h3>カテゴリー</h3>
        </div>
        <div class="form__cat">
            @foreach ($categories as $category)
            <label class="form__cat-button">
                <input type="checkbox"
                    name="category_ids[]"
                    value="{{ $category->id }}"
                    hidden
                    {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                <span>{{ $category->content }}</span>
            </label>
            @endforeach
        </div>
        @error ('category_ids')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__header--secondary">
            <h3>商品の状態</h3>
        </div>
        <select name="condition_id" class="form__condition-select">
            <option class="form__condition-select--item" value="">選択してください</option>
            @foreach ($conditions as $condition)
            <option class="form__condition-select--item" value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->content }}</option>
            @endforeach
        </select>
        @error ('condition_id')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__header--primary">
            <h2>商品名と説明</h2>
        </div>
        <div class="form__header--secondary">
            <h3>商品名</h3>
        </div>
        <div class="form__input">
            <input class="form__input--text" name="name" type="text" value="{{ old('name') }}">
        </div>
        @error ('name')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__header--secondary">
            <h3>ブランド名</h3>
        </div>
        <div class="form__input">
            <input class="form__input--text" name="brand" type="text" value="{{ old('brand') }}">
        </div>
        <div class="form__header--secondary">
            <h3>商品の説明</h3>
        </div>
        <textarea class="form__input--textarea" name="detail" rows="10">{{ old('detail') }}</textarea>
        @error ('detail')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__header--secondary">
            <h3>販売価格</h3>
        </div>
        <div class="form__input">
            <input class="form__input--text form__input-price" name="price" type="text" placeholder="￥" value="{{ old('price') }}">
        </div>
        @error ('price')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <div class="form__button">
            <button class="form__button-submit">出品する</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    const fileInput = document.getElementById('file');
    const preview = document.querySelector('.form__img-preview');

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];

        if (!file) return;

        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
        document.querySelector('.form__img-button').style.display = 'none';
    })
</script>
@endsection