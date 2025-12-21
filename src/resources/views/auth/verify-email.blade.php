<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ">
        </div>
    </header>

    <main>
        <div class="verify-email__content">
            <div class="verify-email__inner">
                <div class="verify-email__message">
                    <h2>登録していただいたメールアドレスに認証メールを送付しました。<br />
                        メール認証を完了してください。
                    </h2>
                </div>
                <div class="verify-email__button">
                    <a href="http://localhost:8025" target="_blank" class="verify-email__button--submit">認証はこちらから</a>
                </div>
                <form action="/email/verification-notification" method="post">
                    @csrf
                    <div class="verify-email__resend">
                        <button class="verify-email__button--resend" type="submit">認証メールを再送する</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>