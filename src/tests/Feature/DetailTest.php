<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

use Tests\TestCase;

 

class DetailTest extends TestCase
{

    use RefreshDatabase;

    public function testDetail()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 1500,
            'description' => 'これはテスト商品です。',
            'condition' => 1,
            'img' => 'test.jpg',
        ]);
        $category = Category::factory()->create(['name' => 'テストカテゴリ']);
        $item->categories()->attach($category);

        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'テストコメント'
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // ページへアクセス
        $response = $this->get(route('detail', $item->id));

        // レスポンス確認
        $response->assertStatus(200);

        // 各項目の表示確認
        $response->assertSee('テスト商品');
        $response->assertSee('¥1,500（税込）');
        $response->assertSee('テストカテゴリ');
        $response->assertSee('良好');
        $response->assertSee('これはテスト商品です。');
        $response->assertSee('1 いいね');
        $response->assertSee('1 コメント');
        $response->assertSee('テストコメント');
    }

    /** @test */
    public function 商品詳細ページで画像が表示される()
    {
        // テストデータ作成
        $item = Item::factory()->create(['img' => 'test.jpg']);

        // ページへアクセス
        $response = $this->get(route('detail', $item->id));

        // 画像URLの確認
        $response->assertSee(asset('storage/test.jpg'));
    }

    /** @test */
    public function 商品詳細ページでいいねが機能する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        Auth::login($user);

        // いいねを実行
        $response = $this->post(route('item.like', $item->id));
        $response->assertRedirect();

        // データベースにいいねが存在するか確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 商品詳細ページでコメントが送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        Auth::login($user);

        // コメント投稿
        $response = $this->post('/item/' . $item->id . '/comment', [
            'comment' => '新しいコメント',
        ]);

        $response->assertRedirect();

        // データベースにコメントが存在するか確認
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '新しいコメント',
        ]);
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
