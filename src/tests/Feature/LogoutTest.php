<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogoutTest extends TestCase
{

    use RefreshDatabase;

    public function testLogout()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('correctpassword')
        ]);

        // ユーザーをログインさせる
        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'correctpassword'
        ]);

        // ログイン後、ユーザーが認証されているか確認
        $this->assertAuthenticatedAs($user);

        // ログアウト処理を実行
        $response = $this->post('/logout');

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');

        // ログイン後、ユーザーが認証されていないことを確認
        $this->assertGuest();
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
