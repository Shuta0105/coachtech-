<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>{{ $order->item->user->name }} 様</p>

    <p>あなたが出品した商品が購入されました。</p>

    <img
        src="{{ file_exists(public_path('img/' . $order->item->img)) 
            ? asset('img/' . $order->item->img) 
            : asset('storage/' . $order->item->img) }}"
        alt="{{ $order->item->name }}"
        width="300"
        height="300">

    <ul>
        <li>商品名：{{ $order->item->name }}</li>
        <li>価格：{{ number_format($order->item->price) }}円</li>
        <li>支払い方法：{{ $order->paymethod === 1 ? 'コンビニ払い' : 'カード支払い' }}</li>
        <li>購入者：{{ $order->user->name }}</li>
        <li>購入者メールアドレス：{{ $order->user->email }}</li>
    </ul>

    <p>購入日時：{{ $order->created_at->format('Y年m月d日 H:i') }}</p>

    <p>配送先住所：〒{{ $order->post_code }} {{ $order->address }} {{ $order->building ?? '' }}</p>

    <p>発送の準備をお願いします。</p>
</body>

</html>