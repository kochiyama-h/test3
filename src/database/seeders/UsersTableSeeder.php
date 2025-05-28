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
                ];
                DB::table('users')->insert($param);


        $param = [
                  'name' => 'bbb',
                  'email' => 'bbb@bbb',
                  'password' => bcrypt('bbbbbbbb'),                                  
                ];
                DB::table('users')->insert($param);
    
        $param = [
                  'name' => 'ccc',
                  'email' => 'ccc@ccc',
                  'password' => bcrypt('cccccccc'),                                 
                ];
                DB::table('users')->insert($param);
    }
}
