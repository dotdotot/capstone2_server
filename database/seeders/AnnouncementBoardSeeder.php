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

class AnnouncementBoardSeeder extends Seeder
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

        $users = User::whereIn('rank_id', [1,2])->get();
        foreach ($users as $user) {
            # 랜덤 수만큼 공지사항 게시판 생성
            for($i = 0; $i < rand(3, 20); $i++) {
                AnnouncementBoard::create([
                    'club_id' => $club->id,
                    'user_id' => $user->id,
                    'title' => $faker->title,
                    'content' => $faker->text,
                    'hits' => rand(1, 100),
                    'block_comment' => rand(0, 1) === 1 ? true : false,
                ]);
            }
        }
    }
}
