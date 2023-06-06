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
        $club = Club::where('name', 'C403')->first();

        # 공지사항 게시판 추출
        $announcementBoards = AnnouncementBoard::where('club_id', $club->id)->inRandomOrder()->get();
        foreach ($announcementBoards as $announcementBoard) {
            # 랜덤 수만큼 댓글 생성
            for($i = 0; $i < rand(1, 5); $i++) {
                Comment::create([
                    'club_id' => $club->id,
                    'user_id' => User::where('club_id', 1)->inRandomOrder()->select('id')->first()->value('id'),
                    'board_id' => $announcementBoard->id,
                    'content' => $faker->text,
                    'hidden_comment' => rand(0, 1) === 1 ? true : false
                ]);
            }
        }

        # 게시판 추출
        $boards = Board::where('club_id', $club->id)->inRandomOrder()->get();
        foreach($boards as $board) {
            # 랜덤 수만큼 댓글 생성
            for($i = 0; $i < rand(1, 5); $i++) {
                Comment::create([
                    'club_id' => $club->id,
                    'user_id' => User::where('club_id', 1)->inRandomOrder()->select('id')->first()->value('id'),
                    'board_id' => $board->id,
                    'content' => $faker->text
                ]);
            }
        }
    }
}
