<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
                  'name' => 'aaa',
                  'email' => 'aaa@aaa',
                  'password' => bcrypt('aaaaaaaa'), 
                  'postal_code'=> '111-1111',
                  'address'=> 'aaa',
                  'building'=> 'aaa',

                ];
                DB::table('users')->insert($param);


        $param = [
                  'name' => 'bbb',
                  'email' => 'bbb@bbb',
                  'password' => bcrypt('bbbbbbbb'), 
                  'postal_code'=> '222-2222',
                  'address'=> 'bbb',
                  'building'=> 'bbb',                                 
                ];
                DB::table('users')->insert($param);
    
        $param = [
                  'name' => 'ccc',
                  'email' => 'ccc@ccc',
                  'password' => bcrypt('cccccccc'), 
                  'postal_code'=> '333-3333',
                  'address'=> 'ccc',
                  'building'=> 'ccc',                                
                ];
                DB::table('users')->insert($param);
    }
}
