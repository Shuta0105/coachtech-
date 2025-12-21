<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ">
        </div>
    </header>

    <main>
        <div class="register__content">
            <div class="register__header">
                <h1>会員登録</h1>
            </div>
            <form action="/register" class="register__form" method="post">
                @csrf
                <label class="form__label">ユーザー名</label>
                <input class="form__input--text" type="text" name="name" value="{{ old('name') }}">
                @error ('name')
                <div class="form__error">
                    {{ $errors->first('name') }}
                </div>
                @enderror
                <label class="form__label">メールアドレス</label>
                <input class="form__input--text" type="text" name="email" value="{{ old('email') }}">
                @error ('email')
                <div class="form__error">
                    {{ $errors->first('email') }}
                </div>
                @enderror
                <label class="form__label">パスワード</label>
                <input class="form__input--text" type="password" name="password" value="{{ old('password') }}">
                @error ('password')
                <div class="form__error">
                    {{ $errors->first('password') }}
                </div>
                @enderror
                <label class="form__label">確認用パスワード</label>
                <input class="form__input--text" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                @error ('password_confirmation')
                <div class="form__error">
                    {{ $errors->first('password_confirmation') }}
                </div>
                @enderror
                <div class="form__button">
                    <button class="form__button--submit" type="submit">登録する</button>
                </div>
            </form>
            <div class="auth-link">
                <a class="auth-link__login" href="/login">ログインはこちら</a>
            </div>
        </div>
    </main>
</body>

</html>