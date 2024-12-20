@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')


<div class="user">
  <div class="user__box">
  <img src="{{asset('storage/images/' . $user->image) }}" alt="ユーザー画像">
  
    <p><strong>{{ $user->name }}</strong></p>
    <form action="/mypage/profile" method="get">
      @csrf
      <button type="submit">プロフィールを編集</button>
    </form>
  </div>
</div>




<div class="item__navi" >
  <ul class="item__navi__menu">

    <li class="{{ request('tab') === 'sell' ? 'active' : '' }}"><a href="?tab=sell"><strong>出品した商品</strong></a></li>
    <li class="{{ request('tab') === 'buy' ? 'active' : '' }}"><a href="?tab=buy"><strong>購入した商品</strong></a></li>
  </ul>

</div>  


  <div>
      @if(request('tab') === 'sell')          

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





          <!-- <div class="item__content">
  
          <div class="item__content__category">  
            <div class="img">
              <img src="" alt="商品画像">
            </div>
            <p>商品名</p>   
          </div>

        </div> -->

      @endif
  </div>



























@endsection