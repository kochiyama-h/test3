<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>coachtech_test</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header-utilities">
        
          <a class="header__logo" href="/">
           <img src="{{ asset('images/logo.svg') }}" alt="coachtechロゴ">          
          </a>
      

        @if (Auth::check())

       <!-- 検索フォーム -->
        <div class="header__search">
          <form action="{{ url('/') }}" method="GET">
            <input type="text" name="search" placeholder="何をお探しですか？" value="{{ $search ?? '' }}">
            <button type="submit">検索</button>
          </form>
        </div>

        <nav>
          <ul class="header-nav">

              <li class="header-nav__item">
                <form class="form__logout" action="/logout" method="post">
                  @csrf
                    <button class="header-nav__button">ログアウト</button>
                </form>
              </li>
            
              <li class="header-nav__item">
              <form action="/mypage" method="get">
                @csrf
                <button class="header-nav__button">マイページ</button>
              </form>
                
              </li>
              
            
          </ul>
        </nav>

        <div class="header-exhibition__button">
          <form action="/sell" method="get">
            @csrf            
             <button type="submit">出品</button>           
          </form>
        </div>

        @elseif (Request::routeIs('login') || Request::routeIs('register'))
        <!-- ログイン会員登録ページは表示しない -->
      @else
        <!-- 未認証ユーザー用 -->
        <div class="header__search">
          <input type="text" placeholder="何をお探しですか？">
        </div>

        <nav>
          <ul class="header-nav">

              <li class="header-nav__item">
              <div class="header__login">
                <a href="/login" class="header-nav__link">ログイン</a>
              </div>
              </li>
            
              <li class="header-nav__item">
                <a class="header-nav__link" href="/mypage">マイページ</a>
              </li>
              
            
          </ul>
        </nav>

        <div class="header-exhibition__button">
          <form action="/sell" method="get">
            @csrf            
             <button type="submit">出品</button>           
          </form>
        </div>


      @endif

      </div>
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>