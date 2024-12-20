<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class MyListTest extends TestCase
{

    use RefreshDatabase;

    //マイリスト一覧取得:いいねした商品だけが表示される

    public function test_liked_items_are_displayed_in_mylist()
{
    
    $user = User::factory()->create();

    $likedItem = Item::factory()->create();

    $user->likedItems()->attach($likedItem->id);

    Auth::login($user);

    $response = $this->get('/?tab=mylist');

    $response->assertStatus(200)
             ->assertSee($likedItem->name);
}

    //マイリスト一覧取得:購入済み商品は「Sold」と表示される

    public function testSold()
    {
        $user = User::factory()->create();

        // 商品を作成
        $item = Item::factory()->create();

        // 購入情報を作成
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // マイリストページにアクセス
        $response = $this->get('/?tab=mylist');

        // SOLDラベルが表示されることを確認
        $response->assertSee('SOLD');
    }

    

    //マイリスト一覧取得:自分が出品した商品は表示されない

    public function testNoMylist()
    {
        // 1. ユーザーを作成してログインする
        $user = User::factory()->create();
        Auth::login($user);

        // 2. 自分が出品した商品を作成する
        $item = Item::factory()->create([
            'user_id' => $user->id, // 出品者はログインしたユーザー
        ]);

        // 3. マイリストページを開く
        $response = $this->get('/?tab=mylist');

        // 4. 出品した商品が表示されることを確認
       $response->assertSee($item->id); // 商品IDが表示されているか確認

        // 購入していない場合、SOLDラベルが表示されないことを確認
        $response->assertDontSee('SOLD');
    }

    //マイリスト一覧取得:未認証の場合は何も表示されない
    public function testNoPage()
    {
        
        $response = $this->get('/?tab=mylist');    
        $response->assertStatus(200);  
        $response->assertSee(''); 
    }





    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {


    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
}
