<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('price');
            $table->text('description');
            $table->string('img')->nullable();
            $table->tinyInteger('condition')->comment('1: 良好, 2: 目立った傷や汚れなし, 3: やや傷や汚れあり, 4: 状態が悪い');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');            
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
        Schema::dropIfExists('items');
    }
}
