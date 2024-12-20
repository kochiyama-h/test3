@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')



<div class="item__navi" >
  <ul class="item__navi__menu">
    <li><strong>おすすめ</strong></li>
    <li class="item__navi__menu__mylist"><a href="/?tab=mylist"><strong>マイリスト</strong></a></li>
  </ul>
</div>



<div class="item__content">





  @foreach ($items as $item)
  <div class="item__content__category">
  <a href="{{ route('detail', $item->id) }}">
    <div class="img">

    @if (filter_var($item->img, FILTER_VALIDATE_URL))
        <img src="{{ $item->img }}" alt="商品画像">
    @else
        <img src="{{ asset('storage/' . $item->img) }}" alt="商品画像">
    @endif

    

    {{-- 購入済み商品の場合に「SOLD」ラベルを表示 --}}
        @if (in_array($item->id, $soldItemIds))
          <div class="sold-label">SOLD</div>
        @endif

        

        

    </div>    




    
    
    <p>{{$item->name}}</p>
   </a>
    

  </div>
  @endforeach

</div>
@endsection