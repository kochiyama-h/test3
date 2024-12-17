@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form action="{{ route('purchase.item', ['id' => $item->id]) }}" method="post">
    @csrf
<div class="purchase__item">
  <input type="hidden" name="item_id" value="{{ $item->id }}">
  
  <div class="purchase__item__left">
    <div class="purchase__item__left__box">
      <div class="item__box__purchase__img">
         <img src="{{ $item->img }}" alt="商品画像">
      </div>
    
       <div class="item__box__purchase__name">
         <h1>{{ $item->name }}</h1>
         <p class="item__detail__price"> ¥{{ number_format($item->price) }}（税込）</p>
       </div>
  
    </div>
  
    <div class="purchase__item__payment">
      <h2>支払方法</h2>
      <select name="payment" id="payment_method" >
        <option value="" disabled selected>選択してください</option>
        <option value="1">コンビニ払い</option>
        <option value="2">カード支払い</option>
      </select>
  
    </div>
    <div class="form__error">
          @error('payment')
          {{ $message }}
          @enderror
    </div>

    <div class="purchase__item__address">
      <div class="purchase__item__address__box">
        <h2>配送先</h2>        
        <a href="{{ route('address', ['id' => $item->id]) }}">変更する</a>         
      </div>



      <div>
          @php
          $updatedAddress = session('updated_address',
           [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
            ]);
          @endphp

          <div>
            <input class="purchase__item__address__text" type="text" name="postal_code" value="{{ $updatedAddress['postal_code'] ?? $user->postal_code }}"readonly>
          </div>

          <div class="purchase__item__address__flex">
            <input  class="purchase__item__address__text" type="text" name="address" value="{{ $updatedAddress['address'] ?? $user->address }}"readonly>
            <input  class="purchase__item__address__text" type="text" name="building" value="{{ $updatedAddress['building'] ?? $user->building }}"readonly>
          </div>  

          
      </div>
      <div class="form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
      </div>
      <div class="form__error">
          @error('building')
          {{ $message }}
          @enderror
      </div>
      <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
      </div>

  
      

    </div>
   
  </div>  

  <div class="purchase__item__right">
   <table class="purchase__item__right__table">
    <tr>
      <th>商品代金</th>
      <td>¥{{ number_format($item->price) }}（税込）</td>
    </tr>

    <tr>
      <th>支払方法</th>
      <td id="payment_method_display"></td>
    </tr>
   </table>

   <button  class="purchase__item__right__submit" type="submit">購入する</button>


  </div>
  </form>
  </div>

@endsection



<script>
  document.addEventListener("DOMContentLoaded", function() {
    const paymentMethodSelect = document.getElementById("payment_method");
    const paymentMethodDisplay = document.getElementById("payment_method_display");

    paymentMethodSelect.addEventListener("change", function() {
      // 選択した支払方法の値に応じて表示するテキストを設定
      const paymentText = {
        "1": "コンビニ払い",
        "2": "カード支払い"
      };

      // 選択値を取得し、対応する文字列を表示
      const selectedValue = paymentMethodSelect.value;
      paymentMethodDisplay.textContent = paymentText[selectedValue] || ""; // 値がない場合は空白を表示
    });
  });
</script>




