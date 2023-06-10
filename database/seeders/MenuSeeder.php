<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\CommonMoney;
use App\Models\Menu;

use Faker\Factory as Faker;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');
        $clubs = Club::get();

        foreach ($clubs as $club) {
            if($club->name === 'C403') {
                Menu::create([
                    'club_id' => $club->id,
                    'title' => '공지',
                    'type' => 'board',
                    'position' => Menu::where('club_id', $club->id)->count(),
                ]);

                Menu::create([
                    'club_id' => $club->id,
                    'title' => '규칙',
                    'type' => 'bulletin',
                    'position' => Menu::where('club_id', $club->id)->count(),
                ]);

                $image_board = Menu::create([
                    'club_id' => $club->id,
                    'title' => '회비내역',
                    'type' => 'image_board',
                    'position' => Menu::where('club_id', $club->id)->count(),
                ]);
                CommonMoney::create([
                    'club_id' => $club->id,
                    'menu_id' => $image_board->id,
                    'money' => 1000000,
                    'position' => 0
                ]);
            } else {
                for($i = 0; $i < rand(3, 5); $i++) {
                    $type = rand(1, 3);
                    if($type == 1) {
                        $type = 'board';
                    } elseif ($type == 2) {
                        $type = 'bulletin';
                    } elseif($type == 3) {
                        $type = 'image_board';
                    }

                    $menu = Menu::create([
                        'club_id' => $club->id,
                        'title' => $faker->name,
                        'type' => $type,
                        'position' => Menu::where('club_id', $club->id)->count(),
                    ]);
                    if($type === 'image_board') {
                        CommonMoney::create([
                            'club_id' => $club->id,
                            'menu_id' => $menu->id,
                            'money' => 1000000,
                            'position' => Menu::where('club_id', $club->id)->where('type', 'image_board')->count()
                        ]);
                    }
                }
            }
        }
    }
}
