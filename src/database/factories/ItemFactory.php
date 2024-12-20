<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User; 
use App\Models\Category;  


use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,  
            'user_id' => User::factory(),  
            'price' => $this->faker->randomNumber(),  
            'description' => $this->faker->sentence,  
            'img' => $this->faker->imageUrl(),  
            'condition' => $this->faker->randomElement([1, 2, 3, 4]),  
            'category_id' => Category::factory(),  
            
        ];
    }
}
