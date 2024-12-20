<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ItemListTest extends TestCase
{

    use RefreshDatabase;

    //商品一覧取得:全商品を取得できる

    public function testAllItemsAreDisplayed()
{
    
    $items = Item::factory()->count(10)->create();
    $response = $this->get('/'); 

    // ステータスコードが200（OK）であることを確認
    $response->assertStatus(200);

    
    foreach ($items as $item) {
        $response->assertSee($item->name);
        $response->assertSee($item->img);   
    }
}

       

    //商品一覧取得:購入済み商品は「Sold」と表示される
    public function testSoldItemsAreMarkedAsSold()
    {
        // 商品と購入データを作成
        $item = Item::factory()->create();  // 商品を1件作成
        $user = User::factory()->create();  // ユーザーを1件作成

        // 購入データを作成（ユーザーが商品を購入）
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/'); // 商品一覧ページへのURL

        // ステータスコードが200（OK）であることを確認
        $response->assertStatus(200);

        // 購入済み商品のIDがSOLDラベルに表示されるか確認
        $response->assertSee('<div class="sold-label">SOLD</div>', false);  // SOLDラベルのHTMLが表示されることを確認
    }


        //商品一覧取得:自分が出品した商品は表示されない
        public function testLoggedInUserDoesNotSeeOwnItemsInList()
    {
        // ログインしたユーザーとその出品商品を作成
        $user = User::factory()->create();  // ログインするユーザー
        $userItem = Item::factory()->create([   // ユーザーが出品した商品
            'user_id' => $user->id,
        ]);

        // 別のユーザーが出品した商品を作成
        $otherUser = User::factory()->create();  // 別のユーザー
        $otherUserItem = Item::factory()->create([  // 他のユーザーが出品した商品
            'user_id' => $otherUser->id,
        ]);

        // ログインする
        Auth::login($user);

        // 商品一覧ページにアクセス
        $response = $this->get('/'); // 商品一覧ページへのURL

        // ステータスコードが200（OK）であることを確認
        $response->assertStatus(200);

        // ログインしたユーザーが出品した商品が表示されていないことを確認
        $response->assertDontSee($userItem->name);  // ユーザーの出品商品は表示されない

        // 他のユーザーが出品した商品が表示されていることを確認
        $response->assertSee($otherUserItem->name);  // 他のユーザーの出品商品は表示される
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
