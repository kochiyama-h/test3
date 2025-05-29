<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;
use App\Models\ChatMessage;

class AuthController extends Controller
{
    
//会員登録画面表示
    public function register()
    {
    return view ('auth.register');
    }


//会員登録
    public function store(RegisterRequest $request)
    {
        $form = $request->only(['name', 'email', 'password']);   
        
        // パスワードをハッシュ化
        $form['password'] = Hash::make($form['password']);

        $user = User::create($form);
        Auth::login($user);        
        return redirect()->route('profile.setup', $user->id);
    } 
    
    public function showLoginForm()
    {
    return view('auth.login');
    } 

    //会員登録後のプロフィール設定
    public function edit(AddressRequest $request)
    {
        // ログイン中のユーザーを取得
    $user = Auth::user();

    // 画像処理
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        // 古い画像があれば削除
        if ($user->image) {
            Storage::delete('public/images/' . $user->image);
        }

        // 元のファイル名を取得
        $originalName = $request->file('image')->getClientOriginalName();

        // ファイルを保存（ファイル名そのまま）
        $path = $request->file('image')->storeAs('public/images', $originalName);

        // ファイル名をデータベースに保存
        $user->image = $originalName;  // 'images/filename.jpg' のように保存
    }

    // フォームからの入力データを取得
    $user->name = $request->name;
    $user->postal_code = $request->postal_code;
    $user->address = $request->address;
    $user->building = $request->building;
    $user->save();

    return redirect('/');
}

    //ログイン
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');         
        Auth::attempt($credentials);            
        return redirect('/');
        
    }
  
    //トップ画面
    public function index(Request $request)
    {

        // 検索キーワード
    $search = $request->input('search');
    
    // 'tab'クエリパラメータが'mylist'の場合は「いいねした商品」だけを取得
    if ($request->tab === 'mylist' && Auth::check()) {
        // ログインユーザーがいいねした商品を取得
         $items = Auth::user()-> likedItems() ->where('name', 'like', '%' . $search . '%')->get();
    } else {
        $items = Item::where('name', 'like', '%' . $search . '%')
                     ->where(function($query) {
                         $query->whereNull('user_id')  // 出品者がいない商品
                               ->orWhere('user_id', '!=', Auth::id()); // 自分が出品した商品を除外
                     })
                     ->get();
    }

    // 購入済み商品のIDを取得
    $soldItemIds = Purchase::pluck('item_id')->toArray();

    return view('index', [
        'items' => $items,
        'soldItemIds' => $soldItemIds,
        'search' => $search,  
    ]);
        
    }
    
    
    //ログアウト機能
    public function logout(Request $request)
{
    Auth::logout(); 
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    return redirect('/login'); 
}

    //出品画面
    public function exhibit()
{
    $categories = Category::all();
    return view('exhibit', compact('categories')); 
}

//プロフィール画面
public function profile(Request $request)
{
   $user = Auth::user();
   
   // リクエストのtabパラメータを確認
   $tab = $request->query('tab', 'sell');  // 'tab'パラメータが無い場合は'sell'をデフォルトに設定

   // 取引中商品の未読メッセージ総数を計算（全タブで表示するため）
   $tradingNotificationCount = $this->getTradingNotificationCount($user);

   if ($tab === 'sell') {
       // 出品した商品
       $items = Item::where('user_id', $user->id)->get();
       
   } elseif ($tab === 'buy') {
       // 購入した商品
       $items = $user->purchases()->with('item')->get()->pluck('item');
       
   } elseif ($tab === 'trading') {
       // 出品した商品（購入者がいるもの）
       $soldItems = Item::where('user_id', $user->id)
           ->whereHas('purchases')
           ->get();

       // 購入した商品
       $purchasedItems = $user->purchases()->with('item')->get()->pluck('item');

       // 両方を結合し、重複を除去
       $items = $soldItems->concat($purchasedItems)
           ->unique('id')  // 商品IDで重複除去
           ->values();     // インデックスを再設定

       // 各商品の未読メッセージ数と最新受信メッセージ日時を追加
       $items = $items->map(function($item) use ($user) {
           $item->unread_messages_count = $this->getUnreadMessagesCount($item, $user);

           // 自分宛の最新のメッセージの created_at を取得（nullもありうる）
           $latestMessage = ChatMessage::where('item_id', $item->id)
               ->where('receiver_id', $user->id)
               ->orderByDesc('created_at')
               ->first();

           $item->latest_received_at = optional($latestMessage)->created_at ?? now()->subYears(10); // null対策で古い日時
           return $item;
       });

       // 受信日時で並び替え（新しい順）
       $items = $items->sortByDesc('latest_received_at')->values();
   
   } else {
       $items = collect(); // 空のコレクション
   }

   return view('profile', compact('user', 'items', 'tradingNotificationCount')); 
}

    /**
     * 取引中商品の未読メッセージ総数を取得
     */
    private function getTradingNotificationCount($user)
    {
        // 出品した商品で購入者がいるもの
        $soldItems = Item::where('user_id', $user->id)
            ->whereHas('purchases')
            ->get();
            
        // 購入した商品
        $purchasedItems = $user->purchases()->with('item')->get()->pluck('item');
        
        // 全取引中商品
        $tradingItems = $soldItems->concat($purchasedItems);
        
        $totalUnreadCount = 0;
        
        foreach ($tradingItems as $item) {
            $totalUnreadCount += $this->getUnreadMessagesCount($item, $user);
        }
        
        return $totalUnreadCount;
    }

    /**
     * 特定の商品の未読メッセージ数を取得
     */
    private function getUnreadMessagesCount($item, $user)
    {
        return ChatMessage::where('item_id', $item->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
    }


//プロフィール編集画面
public function profileUpdate()
{
    $user = Auth::user(); // 現在ログインしているユーザー情報を取得
    return view('edit', compact('user'));
}

//購入画面
public function purchase(PurchaseRequest $request)
{
    $user = auth()->user();

    // セッションに保存された住所情報を取得
    $address = session('updated_address', [
        'postal_code' => $user->postal_code,
        'address' => $user->address,
        'building' => $user->building,
    ]);

    // purchasesテーブルにデータを保存
    Purchase::create([
        'user_id' => $user->id,
        'item_id' => $request->input('item_id'),
        'postal_code' => $address['postal_code'],
        'address' => $address['address'],
        'building' => $address['building'],
        'payment' => $request->input('payment'),
    ]);

    // セッションから住所情報を削除
    session()->forget('updated_address');

    return redirect('/'); 
}

}
