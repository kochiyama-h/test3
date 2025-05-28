@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="user">
  <div class="user__box">
    <img src="{{ asset('storage/images/' . $user->image) }}" alt="ユーザー画像">
    
    <div class="user__info">
      <p><strong>{{ $user->name }}</strong></p>
      <div class="user__rating">
        @php
          $averageRating = \App\Models\Rating::getAverageRating($user->id);
          $ratingCount = \App\Models\Rating::getRatingCount($user->id);
        @endphp
        
        @if($averageRating !== null)
          @for($i = 1; $i <= 5; $i++)
            @if($i <= $averageRating)
              <span class="star filled">★</span>
            @else
              <span class="star">★</span>
            @endif
          @endfor
          
        @endif
      </div>
    </div>
    
    <form action="/mypage/profile" method="get">
      @csrf
      <button type="submit">プロフィールを編集</button>
    </form>
  </div>
</div>

<div class="item__navi">
  <ul class="item__navi__menu">
    <li class="{{ request('tab') === 'sell' || !request('tab') ? 'active' : '' }}">
      <a href="?tab=sell"><strong>出品した商品</strong></a>
    </li>
    <li class="{{ request('tab') === 'buy' ? 'active' : '' }}">
      <a href="?tab=buy"><strong>購入した商品</strong></a>
    </li>
    <li class="{{ request('tab') === 'trading' ? 'active' : '' }}">
      <a href="?tab=trading">
        <strong>取引中の商品</strong>
        @if(isset($tradingNotificationCount) && $tradingNotificationCount > 0)
          <span class="notification-badge">{{ $tradingNotificationCount }}</span>
        @endif
      </a>
    </li>
  </ul>
</div>  

<div>
  @if(request('tab') === 'sell' || !request('tab'))          
    <div class="item__content">
      @foreach ($items as $item)
        <div class="item__content__category">
          <a href="{{ route('detail', $item->id) }}">
            <div class="item__content__img">
              @if (filter_var($item->img, FILTER_VALIDATE_URL))
                <img src="{{ $item->img }}" alt="商品画像">
              @else
                <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
              @endif
            </div>   
            <div class="item__content__name">
              <p><strong>{{$item->name}}</strong></p>
            </div> 
          </a>
        </div>
      @endforeach
    </div>
        
  @elseif(request('tab') === 'buy')
    <div class="item__content">
      @foreach ($items as $item)
        <div class="item__content__category">
          <a href="{{ route('detail', $item->id) }}">
            <div class="item__content__img">
              @if (filter_var($item->img, FILTER_VALIDATE_URL))
                <img src="{{ $item->img }}" alt="商品画像">
              @else
                <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
              @endif
            </div>   
            <div class="item__content__name">
              <p><strong>{{$item->name}}</strong></p>
            </div> 
          </a>
        </div>
      @endforeach
    </div>
          
  @elseif(request('tab') === 'trading')
    <div class="item__content">
      @foreach ($items as $item)
        <div class="item__content__category">
          <!-- 取引中の商品はチャット画面に遷移 -->
          <a href="{{ route('chat', $item->id) }}">
            <div class="item__content__img">
              @if(isset($item->unread_messages_count) && $item->unread_messages_count > 0)
                <span class="item-notification-badge">{{ $item->unread_messages_count }}</span>
              @endif
              @if (filter_var($item->img, FILTER_VALIDATE_URL))
                <img src="{{ $item->img }}" alt="商品画像">
              @else
                <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
              @endif
            </div>   
            <div class="item__content__name">
              <p><strong>{{$item->name}}</strong></p>
            </div> 
          </a>
        </div>
      @endforeach
    </div>
  @endif
</div>

@endsection