<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class MypageTest extends TestCase
{

    use RefreshDatabase;


    public function testMypage()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'image' => 'test_image.jpg',
            'name' => 'テストユーザー',
            // 'postal_code' => '123-4567',
            // 'address' => '東京都渋谷区',
            // 'building' => 'テストビル123'
        ]);

        $items = Item::factory(3)->create(['user_id' => $user->id]);
        $purchasedItems = Item::factory(3)->create();

        // ログイン
        Auth::login($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('test_image.jpg');

        // 出品した商品一覧ページにアクセス
        $response = $this->get('/mypage/profile?tab=sell');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 出品した商品が正しく表示されることを確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
            
        }

        // 購入した商品一覧ページにアクセス
        $response = $this->get('/mypage/profile?tab=buy');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 購入した商品が正しく表示されることを確認
        foreach ($purchasedItems as $item) {
            $response->assertSee($item->name);
        }
    }

    
    /**
     * A basic feature test example.
     *
     * @return void
     */
   
}
