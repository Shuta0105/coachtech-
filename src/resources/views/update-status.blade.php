@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/update-status.css') }}">
@endsection

@section('content')
<div class="content">
    <h1>配送状況更新画面</h1>
    <h2>対象商品：{{ $order->item->name }}</h2>
    <form action="/update/shipping/status/{{ $order->id }}" method="post">
        @csrf
        <div class="radio-group">
            <label><input type="radio" name="status" value="pending"
                    {{ $order->status === 'pending' ? 'checked' : '' }}>
                未発送
            </label>
            <label><input type="radio" name="status" value="shipped"
                    {{ $order->status === 'shipped' ? 'checked' : '' }}>
                発送済み
            </label>
            <label><input type="radio" name="status" value="delivered"
                    {{ $order->status === 'delivered' ? 'checked' : '' }}>
                配達済み
            </label>
        </div>
        <button class="button-submit" type="submit">変更</button>
    </form>
</div>
@endsection