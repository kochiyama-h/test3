<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LikeItemTest extends TestCase
{

    use RefreshDatabase;

    //いいね機能:いいねアイコンを押下することによって、いいねした商品として登録することができる。

    public function test_like_count_increases()
    {
        
        $item = Item::factory()->create(['name' => 'テスト商品']);
        $user = User::factory()->create();

        
        Auth::login($user);

        
        $initialLikeCount = $item->likes->count();

        $response = $this->post(route('item.like', ['itemId' => $item->id]));

        // いいねが追加されていることを確認
        $item->refresh();  // 商品の情報を再取得
        $this->assertEquals($initialLikeCount + 1, $item->likes->count());

        
    }

    //いいね機能:再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_like_count_decreases()
    {
        
        $item = Item::factory()->create(['name' => 'テスト商品']);
        $user = User::factory()->create();

        
        Auth::login($user);

        
        $user->likes()->create(['item_id' => $item->id]);

        
        $initialLikeCount = $item->likes->count();

        // いいねを解除するために再度ポストリクエストを送信
        $response = $this->post(route('item.like', ['itemId' => $item->id]));

        // いいねが解除されていることを確認
        $item->refresh();  // 商品の情報を再取得
        $this->assertEquals($initialLikeCount - 1, $item->likes->count());

        
    }

    //いいね機能:追加済みのアイコンは色が変化する

    public function test_like_icon_clicked()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Auth::login($user);

       
        $response = $this->get(route('detail', $item->id));
        $response->assertStatus(200);
        $response->assertSee('☆'); // 初期状態では「未いいね」

        // いいねを押下
        $likeResponse = $this->post(route('item.like', $item->id));
        $likeResponse->assertStatus(302); // リダイレクトを確認

        // 再度詳細ページにアクセスしてアイコンが変更されているか確認
        $responseAfterLike = $this->get(route('detail', $item->id));
        $responseAfterLike->assertStatus(200);
        $responseAfterLike->assertSee('★'); // いいね後の状態を確認
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
