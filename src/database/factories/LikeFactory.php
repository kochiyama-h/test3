<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like;
use App\Models\User;
use App\Models\Item;

class LikeFactory extends Factory
{

    protected $model = Like::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // ユーザーIDを生成
            'item_id' => Item::factory(), // アイテムIDを生成
        ];
    }
}
