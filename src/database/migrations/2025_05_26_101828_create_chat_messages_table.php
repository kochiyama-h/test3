<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // 商品ID（外部キー）
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // 送信者ID（外部キー）
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // 受信者ID（外部キー）
            $table->text('message'); // メッセージ内容
            $table->boolean('is_read')->default(false); // 既読フラグ（デフォルト：未読）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}