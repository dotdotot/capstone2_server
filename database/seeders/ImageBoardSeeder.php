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
use App\Models\ImageBoard;
use App\Models\Menu;
use App\Models\CommonMoney;

class ImageBoardSeeder extends Seeder
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
            $imageBoards = Menu::where('club_id', $club->id)->where('type', 'image_board')->get();
            foreach($imageBoards as $imageBoard) {

                $leaderUsers = User::where('club_id', $club->id)
                                                ->whereIn('rank_id', Rank::where('club_id', $club->id)
                                                                                        ->whereIn('name', ['방장', '팀장'])
                                                                                        ->select('id')
                                                                                        ->get()
                                                                                        ->pluck('id')
                                                                                        ->toArray())
                                                ->get();
                foreach ($leaderUsers as $leaderUser) {
                    # 랜덤 수만큼 게시판 생성
                    for($i = 0; $i < rand(1, 5); $i++) {
                        $newImageBoard = new ImageBoard();
                        $newImageBoard->club_id = $club->id;
                        $newImageBoard->user_id = $leaderUser->id;
                        $newImageBoard->menu_id = $imageBoard->id;
                        $newImageBoard->image_id = null;

                        $newImageBoard->title = $faker->borough;
                        $newImageBoard->money = rand(3000, 10000);

                        $newImageBoard->position = ImageBoard::where('club_id', $club->id)
                                                                                            ->where('menu_id', $imageBoard->id)
                                                                                            ->count();

                        $newImageBoard->save();

                        $commonMoney = CommonMoney::where('club_id', $club->id)
                                                                                ->where('menu_id', $imageBoard->id)
                                                                                ->first();
                        $commonMoney->money = $commonMoney->money - $newImageBoard->money;
                        $commonMoney->save();
                    }
                }
            }
        }
    }
}
