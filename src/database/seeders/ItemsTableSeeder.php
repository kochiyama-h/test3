<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
                  'name' => '腕時計',
                  'user_id'=> 1,
                  'price' => 15000,
                  'description' => 'スタイリッシュなデザインのメンズ腕時計',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                  'condition' => 1,                  
                ];
                DB::table('items')->insert($param);
        


        $param = [
                  'name' => 'HDD',
                  'user_id'=> 1,
                  'price' => 5000,
                  'description' => '高速で信頼性の高いハードディスク',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                  'condition' => 2,                  
                  ];
                  DB::table('items')->insert($param);


        $param = [
                  'name' => '新鮮な玉ねぎ3束のセット',
                  'user_id'=> 1,
                  'price' => 300,
                  'description' => '新鮮な玉ねぎ3束のセット',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                  'condition' => 3,                  
                  ];
                  DB::table('items')->insert($param);


        $param = [
                  'name' => '革靴',
                  'user_id'=> 1,
                  'price' => 4000,
                  'description' => 'クラシックなデザインの革靴',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                  'condition' => 4,                  
                  ];
                  DB::table('items')->insert($param);
          
  
  
        $param = [
                  'name' => 'ノートPC',
                  'user_id'=> 1,
                  'price' => 45000,
                  'description' => '高性能なノートパソコン',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                  'condition' => 1,                  
                    ];
                    DB::table('items')->insert($param);
  
  
        $param = [
                  'name' => 'マイク',
                  'user_id'=> 2,
                  'price' => 8000,
                  'description' => '高音質のレコーディング用マイク',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                  'condition' => 2,                  
                    ];
                    DB::table('items')->insert($param);

        $param = [
                 'name' => 'ショルダーバッグ',
                 'user_id'=> 2,
                 'price' => 3500,
                 'description' => 'おしゃれなショルダーバッグ',
                 'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                 'condition' => 3,                  
                   ];
                   DB::table('items')->insert($param);


        $param = [
                  'name' => 'タンブラー',
                  'user_id'=> 2,
                  'price' => 500,
                  'description' => '使いやすいタンブラー',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                  'condition' => 4,                  
                    ];
                    DB::table('items')->insert($param);    


        $param = [
                  'name' => 'コーヒーミル',
                  'user_id'=> 2,
                  'price' => 4000,
                  'description' => '手動のコーヒーミル',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                  'condition' => 1 ,                  
                    ];
                    DB::table('items')->insert($param);    

        $param = [
                  'name' => 'メイクセット',
                  'user_id'=> 2,
                  'price' => 2500,
                  'description' => '便利なメイクアップセット',
                  'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                  'condition' => 2,                  
                    ];
                    DB::table('items')->insert($param);                        
  

           
    }
}


