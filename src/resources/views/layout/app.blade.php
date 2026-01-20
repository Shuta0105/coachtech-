<!DOCTYPE html>
<html lang="ja">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <a href="/">
                    <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="">
                </a>
            </div>
            <div class="search-box">
                <input id="search-input"
                    class="header__input--text"
                    type="text"
                    name="keyword"
                    placeholder="なにをお探しですか？"
                    value="{{ request('keyword') }}"
                    dusk="search-input">
                <select class="select-box" id="select" name="select">
                    <option value="name">名前</option>
                    <option value="price">価格</option>
                    <option value="category">カテゴリー</option>
                </select>
            </div>
            <div class="header__buttons">
                @if (Auth::check())
                <form action="/logout" class="logout__form" method="post">
                    @csrf
                    <button class="header__button--logout">ログアウト</button>
                </form>
                @else
                <a class="header__button--login" href="/login">ログイン</a>
                @endif
                <a class="header__button--mypage" href="/mypage?page=sell">マイページ</a>
                <a class="header__button--sell" href="/sell">出品</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    @yield('js')
</body>

</html>