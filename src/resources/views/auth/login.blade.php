<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ">
        </div>
    </header>

    <main>
        <div class="login__content">
            <div class="login__header">
                <h1>ログイン</h1>
            </div>
            <form action="/login" class="login__form" method="post">
                @csrf
                <label class="form__label">メールアドレス</label>
                <input class="form__input--text" type="text" name="email" value="{{ old('email') }}">
                @error ('email')
                <div class="form__error">
                    {{ $message }}
                </div>
                @enderror
                <label class="form__label">パスワード</label>
                <input class="form__input--text" type="password" name="password" value="{{ old('password') }}">
                @error ('password')
                <div class="form__error">
                    {{ $message }}
                </div>
                @enderror
                <div class="form__button">
                    <button class="form__button--submit" type="submit">ログインする</button>
                </div>
            </form>
            <div class="auth-link">
                <a class="auth-link__register" href="/register">会員登録はこちら</a>
            </div>
        </div>
    </main>
</body>

</html>