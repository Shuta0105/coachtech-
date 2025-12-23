@php use Illuminate\Support\Str; @endphp
@extends('layout.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase__content">
    <form class="purchase-form">
        <div class="purchase__left">
            <div class="purchase__item">
                <div class="purchase__item-img">
                    <img src="{{ Str::startsWith($item->img, 'http') ? $item->img : asset('storage/' . $item->img) }}">
                </div>
                <div class="purchase__item-text">
                    <h1 class="purchase__item-text--name">{{ $item->name }}</h1>
                    <div class="purchase__item-text--price">￥{{ $item->price }}</div>
                </div>
            </div>
            <div class="purchase__method">
                <div class="purchase__method-header">
                    <h2>支払い方法</h2>
                </div>
                <select name="paymethod" class="purchase__method-select" id="paymethod">
                    <option value="">選択してください</option>
                    <option class="purchase__method-select--item" value="1" @selected(old('paymethod')==1)>コンビニ払い</option>
                    <option class="purchase__method-select--item" value="2" @selected(old('paymethod')==2)>カード支払い</option>
                </select>
                <div class="form__error" id="error-paymethod"></div>

            </div>
            <div class="purchase__address">
                <div class="purchase__address-header">
                    <h2>配送先</h2>
                    <a class="purchase__address-change" href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>
                <div class="purchase__address-body">
                    <div>〒{{ $address['post_code'] }}</div>
                    <div>{{ $address['address'] }}{{ $address['building'] }}</div>
                    <input type="hidden" name="post_code" id="post_code" value="{{ $address['post_code'] }}">
                    <input type="hidden" name="address" id="address" value="{{ $address['address'] }}">
                    <input type="hidden" name="building" value="{{ $address['building'] }}">
                </div>
                <div class="form__error" id="error-post_code"></div>
                <div class="form__error" id="error-address"></div>
            </div>
        </div>
        <div class="purchase__right">
            <table class="purchase-table__inner">
                <tr class="purchase-table__row">
                    <th class="purchase-table__header">商品代金</th>
                    <td class="purchase-table__item">￥{{ $item->price }}</td>
                </tr>
                <tr class="purchase-table__row">
                    <th class="purchase-table__header">支払い方法</th>
                    <td class="purchase-table__item" id="selected-paymethod" dusk="selected-paymethod">未選択</td>
                </tr>
            </table>
            <div class="purchase-form__button">
                <button id="checkout-button" class="purchase-form__button-submit" type="button" data-id="{{ $item->id }}">購入する</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('paymethod');
        const display = document.getElementById('selected-paymethod');

        select.addEventListener('change', () => {
            const selectedText = select.options[select.selectedIndex].text;
            display.textContent = selectedText || '未選択';
        });
    });
</script>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const button = document.getElementById('checkout-button');
    const itemId = button.dataset.id;
    const form = document.querySelector('.purchase-form');

    button.addEventListener('click', async (e) => {

        document.querySelectorAll('.form__error').forEach(el => el.textContent = '');

        const formData = new FormData(form);
        const res = await fetch(`/create-checkout-session/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (res.status === 422) {
            const data = await res.json();
            Object.entries(data.errors).forEach(([field, messages]) => {
                const errorEl = document.getElementById(`error-${field}`);
                if (errorEl) {
                    errorEl.textContent = messages[0];
                };
            });
            return;
        }

        const data = await res.json();
        stripe.redirectToCheckout({
            sessionId: data.id
        });
    })
</script>
@endsection