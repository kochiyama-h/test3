<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class ExhibitTest extends TestCase
{

    use RefreshDatabase;


    public function testExhibit()
{
    // ユーザーを作成しログイン
    $user = User::factory()->create();
    Auth::login($user);

    // 必要なカテゴリデータを作成
    $category = Category::factory()->create();

   
    // 出品データ
    $itemData = [
        'name' => 'テストアイテム',
        'description' => 'テスト用の説明文です。',
        'img' => \Illuminate\Http\UploadedFile::fake()->image('test.png'),
        'category_id' => [$category->id], // 適切なカテゴリIDを設定
        'condition' => 1, // 商品状態
        'price' => 3000,
    ];

    

    // 画像データを追加（画像アップロードのシミュレーション）
    // $itemData['img'] = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');

    // 出品処理をシミュレート
    $response = $this->post('/', $itemData);

    // リダイレクト確認
    // $response->assertRedirect('/');

    // データベース確認（items テーブル）
    $this->assertDatabaseHas('items', [
        'name' => $itemData['name'],
        'user_id' => $user->id,
        'price' => $itemData['price'],
        'description' => $itemData['description']
        // 'condition' => $itemData['condition'],
    ]);

    // データベース確認（中間テーブル）
    $this->assertDatabaseHas('category_item', [
        'category_id' => $category->id,
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
