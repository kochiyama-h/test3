<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;

class SearchTest extends TestCase
{

    use RefreshDatabase;

    //商品検索機能:「商品名」で部分一致検索ができる

    public function test_search_items_by_name()
    {
        
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '特別商品']);

        
        $searchQuery = '商品';

        
        $response = $this->get('/?search=' . $searchQuery);

        
        $response->assertStatus(200);

        
        $response->assertSeeText('商品A');
        $response->assertSeeText('商品B');
        $response->assertSeeText('特別商品');
        
        
    }

    //商品検索機能:検索状態がマイリストでも保持されている
    public function test_search_query_is_retained_on_mylist_page()
    {
        
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);
        $item3 = Item::factory()->create(['name' => '特別商品']);

        
        $searchQuery = '商品';

        
        $response = $this->get('/?search=' . $searchQuery);

        
        $response->assertStatus(200);
        $response->assertSeeText('商品A');
        $response->assertSeeText('商品B');
        $response->assertSeeText('特別商品');

        
        $response = $this->get('/?search=' . $searchQuery . '&tab=mylist');

        
        $response->assertStatus(200);
        $response->assertSee($searchQuery); 
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
