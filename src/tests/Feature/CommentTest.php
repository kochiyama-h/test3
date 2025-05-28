<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentTest extends TestCase
{

    use RefreshDatabase;


    //コメント送信機能:ログイン済みのユーザーはコメントを送信できる
    public function test_comment_submit()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 初期状態でコメントがないことを確認
        $this->assertCount(0, $item->comments);

        Auth::login($user);

        $commentData = ['comment' => '素晴らしい商品です！'];

        // コメント送信リクエストを送信
        $response = $this->post('/item/' . $item->id . '/comment', $commentData);

        $response->assertStatus(302);
        $response->assertRedirect('/item/' . $item->id);

        // データベースにコメントが保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => $commentData['comment'],
        ]);

        $this->assertCount(1, $item->refresh()->comments);
    }

    //コメント送信機能:ログイン前のユーザーはコメントを送信できない
    public function test_comment_notsubmit()
{
    $item = Item::factory()->create();

    $commentData = ['comment' => '素晴らしい商品です！'];

    // 未ログイン状態でコメント送信リクエストを送信
    $response = $this->post('/item/' . $item->id . '/comment', $commentData);

    // リダイレクト先がログイン画面であることを確認
    $response->assertRedirect('/login');

    // コメントが保存されていないことを確認
    $this->assertDatabaseMissing('comments', [
        'item_id' => $item->id,
        'content' => $commentData['comment'],
    ]);
}





    //コメント送信機能:コメントが入力されていない場合、バリデーションメッセージが表示される

    public function test_comment_is_empty()
{
    $user = User::factory()->create();
    $item = Item::factory()->create();

    Auth::login($user);

    $response = $this->post('/item/' . $item->id . '/comment', [
        'comment' => '',
    ]);

    $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);

    // コメントが保存されていないことを確認
    $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);
}


    //コメント送信機能:コメントが255字以上の場合、バリデーションメッセージが表示される

    

    public function test_comment_is_overcount()
    {
        
        $user = User::factory()->create();
        $item = Item::factory()->create();

        
        Auth::login($user);

        // 256文字以上のコメントでリクエスト送信
        $longComment = str_repeat('a', 256);
        $response = $this->post('/item/' . $item->id . '/comment', [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment' => '255文字以内で入力してください']);

        // コメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);
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
