<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;
use App\Models\Rank;
use App\Models\Board;
use App\Models\Bulletin;
use App\Models\Menu;

class BulletinSeeder extends Seeder
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

        foreach($clubs as $club) {
            $clubBelletins = Menu::where('club_id', $club->id)->where('type', 'bulletin')->get();
            foreach($clubBelletins as $clubBelletin) {

                $leaderUser = User::where('club_id', $club->id)
                                                ->where('rank_id', Rank::where('club_id', $club->id)
                                                                                        ->where('name', '방장')
                                                                                        ->value('id'))
                                                ->first();
                Bulletin::create([
                    'club_id' => $club->id,
                    'user_id' => $leaderUser->id,
                    'menu_id' => $clubBelletin->id,
                    'title' => $faker->metropolitanCity,
                    'content' => $faker->text,
                    'position' => Board::where('club_id', $club->id)
                                                    ->where('menu_id', $clubBelletin->id)
                                                    ->count()
                ]);
            }
        }
    }
}
