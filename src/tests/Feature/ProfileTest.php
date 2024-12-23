<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    use RefreshDatabase;

    public function testProfile()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'image' => 'test_image.jpg',
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル123'
        ]);

        // ログイン
        Auth::login($user);

        // プロフィールページにアクセス
        $response = $this->get('/mypage/profile');

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 各項目の初期値が正しいことを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テストビル123');

        // 画像のパスが正しいことを確認
        $response->assertSee('test_image.jpg');
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
