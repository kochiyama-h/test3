<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


use Illuminate\Support\Facades\Auth;

class PurchaseTest extends TestCase
{

    use RefreshDatabase;

    //商品購入機能：「購入する」ボタンを押下すると購入が完了する
    public function test_purchase_button()
    {
        
        $user = User::factory()->create();
        $item = Item::factory()->create();

        
        Auth::login($user);

       
        $purchaseData = [
            'item_id' => $item->id,
            'payment' => '1', // コンビニ払い
        ];

        // 購入処理を実行
        $response = $this->post(route('purchase.item', ['id' => $item->id]), $purchaseData);

        
        $response->assertStatus(302);
    }


    //商品購入機能：購入した商品は商品一覧画面にて「sold」と表示される


    public function test_purchase_sold()
    {
        
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Auth::login($user);

        
        $purchaseData = [
            'item_id' => $item->id,
            'payment' => 2, 
            'postal_code' => '123-4567', 
            'address' => '東京都', 
            'building' => 'ビル名1', 
        ];

        // 購入処理を実行
        $this->post(route('purchase.item', ['id' => $item->id]), $purchaseData);

        // 商品一覧画面にアクセス
        $response = $this->get('/');

        // ステータスコード確認
        $response->assertStatus(200);

       

        $response->assertSee('SOLD');
    }

     //商品購入機能：「プロフィール/購入した商品一覧」に追加されている
    public function test_purchase_profilepage()
    {
        
        $user = User::factory()->create();
        $item = Item::factory()->create();

       
        Auth::login($user);

        // 商品購入処理
        $purchaseData = [
            'item_id' => $item->id,
                        
        ];

        // 商品購入を実行
        $this->post(route('purchase.item', ['id' => $item->id]), $purchaseData);

        
        $response = $this->get('/mypage?tab=buy');

        $response->assertStatus(200);

        $response->assertSee($item->id); 
        
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
