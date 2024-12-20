<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class RegisterTest extends TestCase
{

    use RefreshDatabase;


//会員登録機能:名前が入力されていない場合、バリデーションメッセージが表示される
    public function testNameRegister()
    {
        
        $response = $this->post('/register', [
            'name' => '', // 空の名前
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    //会員登録機能:メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function testEmailRegister()
    {
        
        $response = $this->post('/register', [
            'name' => 'test', 
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    //会員登録機能:パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function testPasswordRegister()
    {
        
        $response = $this->post('/register', [
            'name' => 'test', 
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }


     //会員登録機能:パスワードが7文字以下の場合、バリデーションメッセージが表示される
     public function testShortpasswordRegister()
     {
         
         $response = $this->post('/register', [
             'name' => 'test', 
             'email' => 'test@example.com',
             'password' => 'short',
             'password_confirmation' => 'short',
         ]);
 
         // ステータスコードが302（リダイレクト）であることを確認
         $response->assertStatus(302);
 
         $response->assertSessionHasErrors([
             'password' => 'パスワードは8文字以上で入力してください',
         ]);
     }


     //会員登録機能:パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     public function testNotsamepasswordRegister()
     {
         
         $response = $this->post('/register', [
             'name' => 'test', 
             'email' => 'test@example.com',
             'password' => 'password123',
             'password_confirmation' => 'password1234',
         ]);
 
         // ステータスコードが302（リダイレクト）であることを確認
         $response->assertStatus(302);
 
         $response->assertSessionHasErrors([
             'password_confirmation' => 'パスワードと一致しません',
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
