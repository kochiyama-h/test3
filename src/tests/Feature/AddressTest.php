<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class AddressTest extends TestCase
{

    use RefreshDatabase;
//配送先変更機能：送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(); 

        
        session([
            'updated_address' => [
                'postal_code' => '123-4567',
                'address' => '東京都',
                'building' => 'ビル'
            ]
        ]);

        Auth::login($user);

        $response = $this->get(route('purchase', ['id' => $item->id]))  
            ->assertSee('123-4567')
            ->assertSee('東京都')  
            ->assertSee('ビル')  ;
            
    }

//配送先変更機能：購入した商品に送付先住所が紐づいて登録される



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
