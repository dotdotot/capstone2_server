<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;
use App\Models\Rank;
use App\Models\AnnouncementBoard;
use App\Models\Board;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');
        $club = Club::where('name', 'C403')->first();

        $users = User::inRandomOrder()->limit(20)->get();
        foreach ($users as $user) {
            # 랜덤 수만큼 게시판 생성
            for($i = 0; $i < rand(1, 5); $i++) {
                Board::create([
                    'club_id' => $club->id,
                    'user_id' => $user->id,
                    'title' => $faker->title,
                    'content' => $faker->text,
                    'hits' => rand(1, 20),
                ]);
            }
        }
    }
}
