@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

<div class="item__detail">

    <div class="item__detail__box">
      <div class="item__detail__img">
          <!-- <img src="{{ $item->img }}" alt="商品画像"> -->

          @if (filter_var($item->img, FILTER_VALIDATE_URL))
              <img src="{{ $item->img }}" alt="商品画像">
          @else
              <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
          @endif


      </div>
    </div>

    <div class="item__detail__box">
      
      <h1>{{ $item->name }}</h1>
      <p>ブランド名</p>
      
      
      <p class="item__detail__price"> ¥{{ number_format($item->price) }}（税込）</p> 

     <!-- いいねボタン -->

     <div class="like-comment__button">


       <div>
         <form action="{{ route('item.like', $item->id) }}" method="POST">
              @csrf
              @if(Auth::check() && Auth::user()->hasLiked($item->id))
                  <button type="submit" class="item__detail__like-button active">★</button>
              @else
                  <button type="submit" class="item__detail__like-button">☆</button>
              @endif
          </form>
          <p>{{ $likesCount }} いいね</p>
       </div>
  
       <!-- コメントアイコンとコメント数 -->
       <div class="item__detail__comment-section">
           <!-- コメントアイコン -->
           <button class="item__detail__comment-icon">
               <i class="fas fa-comment"></i>
           </button>
           <!-- コメント数 -->
           <p>{{ $commentsCount }} コメント</p>
       </div>

     </div>  





      <form action="{{ route('purchase', ['id' => $item->id]) }}" method="get">        
        <button class="item__detail__purchase-button" type="submit">購入手続きへ</button>
      </form>
  
      <div class="item__detail__description">
        <h2>商品説明</h2>
        <p>{{ $item->description }}</p>
      </div>
  
      
  
      <h2>商品の情報</h2>
  
      <div class="item__detail__category">
        <h3>カテゴリー</h3>
        @foreach ($categories as $category)
          <p>{{ $category->name }}</p>        
        @endforeach
      </div>
  
      <div class="item__detail__condetion">
        <h3>商品の状態</h3>
        <p>
          @if($item->condition === 1)
              良好
          @elseif($item->condition === 2)
              目立った傷や汚れなし
          @elseif($item->condition === 3)
              やや傷や汚れあり
          @elseif($item->condition === 4)
              状態が悪い
          @endif
        </p>
      </div>

      <div class="item__detail__comment">
        <p>コメント数: {{ $item->comments->count() }}</p>
        
        @foreach($item->comments as $comment)
            <p>{{ $comment->user->name }}</p>
            <input type="text"  class="item__detail__comment__content" placeholder="こちらにコメントが入ります" value="{{ $comment->content }}" readonly>
        @endforeach

      </div>
  
  
  
      <h3>商品へのコメント</h3>
      
      <form action="{{ url('/item/' . $item->id . '/comment') }}" method ="POST">  

        @csrf    
      <textarea cols="60" rows="15" name="comment" class=""></textarea>
  
      <div>
        <button class="item__detail__comment-button" type="submit">コメントを送信する</button>
      </div>
    </form>

    </div>

</div>

@endsection