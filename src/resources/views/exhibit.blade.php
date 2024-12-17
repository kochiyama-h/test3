@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibit.css') }}">
@endsection

@section('content')
<div class="exhibit__content">
  <div class="exhibit-form__heading">
    <h2>商品の出品</h2>
  </div>
  <form class="form" action="/" method="post" enctype="multipart/form-data">
    @csrf  
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>商品画像</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="file" name="img" />
        </div>
        <div class="form__error">
          @error('img')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <h3 class="exhibit__h3">商品の詳細</h3>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>カテゴリー</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">

        <div class="form__input--text">          

            @foreach ($categories as $category)
                <input 
                    type="checkbox" 
                    id="category-{{ $category->id }}" 
                    name="category_id[]" 
                    value="{{ $category->id }}" 
                    class="hidden-checkbox"
                    {{ in_array($category->id, old('category_id', $selectedCategories ?? [])) ? 'checked' : '' }}>
                <label for="category-{{ $category->id }}" class="custom-button">
                    {{ $category->name }}
                </label>
            @endforeach

        </div>

        

        </div>
        <div class="form__error">
          @error('category_id')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>




    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>商品の状態</strong></span>
      </div>
      <div class="form__group-content">
        

        <div class="form__input--text">
            <select name="condition" id="">
                <option value="" disabled {{ old('condition') == '' ? 'selected' : '' }}>選択してください</option>
                <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
            </select>
        </div>






        <div class="form__error">
          @error('condition')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <h3 class="exhibit__h3">商品名と説明</h3>

    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>商品名</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="name" value="{{ old('name') }}" />
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
        <span class="form__label--item"><strong>商品の説明</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
        <textarea cols="60" rows="15" name="description"  value="">{{ old('description') }}</textarea>
          
        </div>
        <div class="form__error">
          @error('description')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item"><strong>販売価格</strong></span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="price"  placeholder="￥" value="{{ old('price') }}"/>
        </div>
        <div class="form__error">
          @error('price')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    
    <div class="form__button">
      <button class="form__button-submit" type="submit">出品する</button>
    </div>
  </form>
  
</div>
@endsection