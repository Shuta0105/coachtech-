@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/check-status.css') }}">
@endsection

@section('content')
<div class="content">
    <h1>配送状況確認画面</h1>
    <h2>対象商品：{{ $order->item->name }}</h2>
    <div class="radio-group">
        <label><input type="radio" name="status" value="pending"
                {{ $order->status === 'pending' ? 'checked' : '' }} disabled>
            未発送
        </label>
        <label><input type="radio" name="status" value="shipped"
                {{ $order->status === 'shipped' ? 'checked' : '' }} disabled>
            発送済み
        </label>
        <label><input type="radio" name="status" value="delivered"
                {{ $order->status === 'delivered' ? 'checked' : '' }} disabled>
            配達済み
        </label>
    </div>
</div>
@endsection