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
use App\Models\Comment;

class CommentSeeder extends Seeder
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
            # 게시판 추출
            $boards = Board::where('club_id', $club->id)->inRandomOrder()->get();
            foreach($boards as $board) {
                $user = User::where('club_id', $club->id)
                                    ->inRandomOrder()
                                    ->first();
                # 랜덤 수만큼 댓글 생성
                for($i = 0; $i < rand(1, 5); $i++) {
                    Comment::create([
                        'club_id' => $club->id,
                        'user_id' => $user->id,
                        'board_id' => $board->id,
                        'content' => $faker->text,
                        'hidden_comment' => rand(1, 2) === 1 ? true : false
                    ]);
                }
            }
        }
    }
}
