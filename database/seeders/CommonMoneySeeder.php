<?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// use Faker\Factory as Faker;

// use App\Models\Club;
// use App\Models\Department;
// use App\Models\User;
// use App\Models\Rank;
// use App\Models\AnnouncementBoard;
// use App\Models\Board;
// use App\Models\Menu;

// class CommonMoneySeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      *
//      * @return void
//      */
//     public function run()
//     {
//         $faker = Faker::create('ko_KR');
//         $clubs = Club::get();

//         foreach($clubs as $club) {
//             $clubBoards = Menu::where('club_id', $club->id)->where('type', '')->get();
//             foreach($clubBoards as $clubBoard) {

//                 # 랜덤 수만큼 게시판 생성
//                 Board::create([
//                     'club_id' => $club->id,
//                     'user_id' => $user->id,
//                     'menu_id' => $clubBoard->id,
//                     'title' => $faker->borough,
//                     'content' => $faker->text,
//                     'hits' => rand(1, 20),
//                     'position' => Board::where('club_id', $club->id)
//                                                     ->where('menu_id', $clubBoard->id)
//                                                     ->count(),
//                     'image' => false,
//                     'block_comment' => rand(1, 2) === 1 ? 'true' : 'false'
//                 ]);
//             }
//         }
//     }
// }
