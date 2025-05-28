<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    //ログイン機能：メールアドレスが入力されていない場合、バリデーションメッセージが表示される

    public function testEmailLogin()
    {
        
        $response = $this->post('/login', [
            
            'email' => '',
            'password' => 'password123',
            
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }


    //ログイン機能：パスワードが入力されていない場合、バリデーションメッセージが表示される

    public function testPasswordLogin()
    {
        
        $response = $this->post('/register', [
            
            'email' => 'test@example.com',
            'password' => '',
            
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }


    


    public function testNotLogin()
    {
        // ログインページを開く
        $response = $this->get('/login');

        // ログインページのレスポンスが正しいか確認（ログインページのURLが'/login'の場合）
        $response->assertStatus(200);

        // 不正なログイン情報を送信（メールアドレスが存在しないもの）
        $response = $this->post('/login', [
            'email' => 'nonexistentuser@example.com', // 存在しないメールアドレス
            'password' => 'incorrectpassword',        // 不正なパスワード
        ]);

        // バリデーションメッセージが表示されることを確認
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }

    public function testLogin()
    {
        // テスト用のユーザーを作成
        $user = User::factory()->create([
            'email' => 'testuser@example.com', // 正しいメールアドレス
            'password' => bcrypt('correctpassword') // 正しいパスワード
        ]);

        // ログインページを開く
        $response = $this->get('/login');
        $response->assertStatus(200); // ログインページが正しく表示されるか確認

        // 正しい情報でログインする
        $response = $this->post('/login', [
            'email' => 'testuser@example.com', // 正しいメールアドレス
            'password' => 'correctpassword'    // 正しいパスワード
        ]);

        // ログイン後にホームページにリダイレクトされるか確認
        $response->assertRedirect('/'); // ログイン後にリダイレクトされるページが'/'であることを確認

        // ログイン後、ユーザーが認証されているか確認
        $this->assertAuthenticatedAs($user); // 正しいユーザーが認証されているか確認
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
