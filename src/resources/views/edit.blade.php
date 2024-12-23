@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')



<div class="register__content">
  <div class="register-form__heading">
    <h2>プロフィール設定</h2>
  </div>
  <form class="form" action="/mypage/profile" method="post" enctype="multipart/form-data">
    @csrf      

    <div class="form__group">      
      <div class="form__group-content">
        <div class="form__input--text">

        <img src="{{ asset('storage/images/' . $user->image) }}" alt="ユーザー画像">

                 
          <input type="file" name="image" id="image" value="{{ old('image', $user->image ?? '') }}" />
        </div>
        <div class="form__error">
          @error('image')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>




    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>ユーザー名</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" />
        </div>
        <div class="form__error">
          @error('name')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>郵便番号</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}" />
        </div>
        <div class="form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>住所</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}"/>
        </div>
        <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>建物名</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building', $user->building ?? '') }}"/>
        </div>
        <div class="form__error">
          @error('building')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
  
</div>



@endsection