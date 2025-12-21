@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endsection

@section('content')
<div class="profile-setup__content">
    <div class="profile-setup__header">
        <h1>プロフィール設定</h1>
    </div>
    <form action="/mypage/profile" class="profile-setup__form" method="post" enctype="multipart/form-data">
        @csrf
        <!-- if文 -->
        @if ($user_profile)
        <div class="profile-image">
            <img class="profile-image__preview"
                src="{{ $user_profile->avatar ? asset('storage/' . $user_profile->avatar) : asset('img/kkrn_icon_user_6.png') }}"
                alt="プロフィール画像">
            <input type="file" name="avatar" id="image" hidden>
            <label class="profile-image__button" for="image">画像を選択する</label>
        </div>
        @error ('avatar')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">ユーザー名</label>
        <input class="form__input--text" type="text" name="name" value="{{ $user_profile->user->name }}">
        @error ('name')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">郵便番号</label>
        <input class="form__input--text" type="text" name="post_code" value="{{ $user_profile->post_code }}">
        @error ('post_code')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">住所</label>
        <input class="form__input--text" type="text" name="address" value="{{ $user_profile->address }}">
        @error ('address')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">建物名</label>
        <input class="form__input--text" type="text" name="building" value="{{ $user_profile->building }}">
        <!-- else文 -->
        @else
        <div class="profile-image">
            <img class="profile-image__preview"
                src="{{ asset('img/kkrn_icon_user_6.png') }}"
                alt="プロフィール画像">
            <input type="file" name="avatar" id="image" hidden>
            <label class="profile-image__button" for="image">画像を選択する</label>
        </div>
        <label class="form__label">ユーザー名</label>
        <input class="form__input--text" type="text" name="name">
        @error ('name')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">郵便番号</label>
        <input class="form__input--text" type="text" name="post_code">
        @error ('post_code')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">住所</label>
        <input class="form__input--text" type="text" name="address">
        @error ('address')
        <div class="form__error">
            {{ $message }}
        </div>
        @enderror
        <label class="form__label">建物名</label>
        <input class="form__input--text" type="text" name="building">
        @endif
        <button class="form__button--submit">更新する</button>
    </form>
</div>
@endsection

@section('js')
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const preview = document.querySelector('.profile-image__preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
        };
    });
</script>
@endsection