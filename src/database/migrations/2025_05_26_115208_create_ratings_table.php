<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade'); // 評価する人
            $table->foreignId('rated_id')->constrained('users')->onDelete('cascade'); // 評価される人
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // 商品
            $table->integer('rating'); // 評価（1-5）
            $table->text('comment')->nullable(); // コメント（オプション）
            $table->timestamps();
            
            // 同じ取引で同じ人が複数回評価できないようにする
            $table->unique(['rater_id', 'rated_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}