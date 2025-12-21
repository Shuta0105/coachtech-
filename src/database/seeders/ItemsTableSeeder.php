<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

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
            'price' => 15000,
            'brand' => 'Rolax',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'detail' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => '革靴',
            'price' => 4000,
            'brand' => '',
            'detail' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => '',
            'detail' => '高性能なノートパソコン',
            'condition_id' => 1,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'マイク',
            'price' => 8000,
            'brand' => 'なし',
            'detail' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'condition_id' => 3,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => 'なし',
            'detail' => '使いやすいタンブラー',
            'condition_id' => 4,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'condition_id' => 1,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg'
        ];
        Item::create($param);
        $param = [
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => '',
            'detail' => '便利なメイクアップセット',
            'condition_id' => 2,
            'img' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg'
        ];
        Item::create($param);
    }
}
