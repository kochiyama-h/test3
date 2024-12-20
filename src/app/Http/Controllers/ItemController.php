<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    //商品詳細画面
    public function itemDetail($id)
    {
        // 商品IDで商品を取得（コメントとカテゴリーも一緒に取得）
    $item = Item::with ('comments', 'categories')->findOrFail($id);

    // 現在ログインしているユーザーがその商品にいいねしているかをチェック
    $hasLiked = Auth::check() && Auth::user()->hasLiked($item->id);

    // 商品のいいね数を取得
    $likesCount = $item->likes()->count();

    // 商品のコメント数を取得
    $commentsCount = $item->comments->count();

    // 商品のカテゴリーを取得
    $categories = $item->categories;

    // 商品詳細ページを表示
    return view('detail', compact('item', 'likesCount', 'commentsCount', 'hasLiked', 'categories'));
    }
    


    //購入画面
    public function purchase($id)
    {
        $item = Item::findOrFail($id);
        
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('purchase', compact('item', 'user'));     

    }

    //住所変更画面
    public function address($id)
    { 
        return view('address', ['id' => $id]);
    }

    //出品画面
    public function exhibit(ExhibitionRequest $request)
    { 
        $form = $request->except('img', 'category_id');  //必要なデータを除外

        $form['user_id'] = auth()->id(); 

        if ($request->hasFile('img')) {
            $form['img'] = $request->file('img')->store('images', 'public');
        }
        $item = Item::create($form); //アイテムを作成
        $item->categories()->sync($request->input('category_id'));  //中間テーブルに保存
        return redirect('/');

    }

    //コメント送信画面

    public function comment(CommentRequest $request,$id)
    {

        // 商品を取得
        $item = Item::findOrFail($id);       

        Comment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(), // 現在ログイン中のユーザーIDを取得
            'content' => $request->input('comment'),
        ]);

        return redirect('item/'. $item->id );

    }

    //購入画面の住所変更
    public function addressUpdate(Request $request, $id)
    {
        // 入力されたデータをセッションに保存
        session([
            'updated_address' => [
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ],
        ]);
        
        
        return redirect()->route('purchase', ['id' => $id]);
        
        
    }

    //いいね機能
    public function likeItem($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        // すでにいいねしていれば削除、していなければ追加
        $like = $user->likes()->where('item_id', $itemId)->first();
        if ($like) {
            $like->delete();  // いいね解除
        } else {
            $user->likes()->create(['item_id' => $itemId]);  // いいね追加
        }

        return back();  // 商品詳細画面にリダイレクト
    }

  

    

}
