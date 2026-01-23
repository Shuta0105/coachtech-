<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>{{ $new_item->user->name }} 様</p>

    <p>以下の商品が正常に出品されました。</p>

    <img
        src="{{ asset('storage/' . $new_item->img) }}"
        alt="{{ $new_item->name }}"
        width="300"
        height="300">

    <p>
        商品名：{{ $new_item->name }}<br>
        価格：{{ number_format($new_item->price) }}円<br>
        説明：{{ $new_item->detail }}
    </p>

    <p>ご利用ありがとうございます。</p>
</body>

</html>