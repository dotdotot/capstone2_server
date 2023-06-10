<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

use DateTime;
use Carbon\Carbon;

use Faker\Factory as Faker;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;
use App\Models\Rank;
use App\Models\Team;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');
        $clubs = Club::select('id', 'name')->get();

        # 클럽 순회
        foreach($clubs as $club) {
            if($club->name === 'C403') {
                # 컴퓨터 공학과 추출
                $department = Department::where('name', '컴퓨터공학과')->first();

                # normal rank는 많이 사용되므로 추출
                $normalRankId = Rank::where('club_id', $club->id)
                                                    ->where('name', '일반')
                                                    ->value('id');

                $users = [
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => collect(
                            Rank::where('club_id', $club->id)
                                    ->where('name', '방장')
                                    ->select('id')
                                    ->first()
                        )->first(),
                        'name' => '차정준',
                        'student_id' => 2161045,
                        'gender' => '남자',
                        'phone' => [
                            '010-4043-0557'
                        ],
                        'email' => '2161045@pcu.ac.kr',
                        'password' => '2161045@pcu.ac.kr',
                        'address' => '대전 서구',
                        'birth_date' => '1997-05-20',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => collect(
                            Rank::where('club_id', $club->id)
                                    ->where('name', '팀장')
                                    ->select('id')
                                    ->first()
                        )->first(),
                        'name' => '임준형',
                        'student_id' => 1961049,
                        'gender' => '남자',
                        'phone' => [
                            '010-5670-9325'
                        ],
                        'email' => '1961049@pcu.ac.kr',
                        'password' => '1961049@pcu.ac.kr',
                        'address' => '대전 서구',
                        'birth_date' => '2000-03-29',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => collect(
                            Rank::where('club_id', $club->id)
                                    ->where('name', '팀장')
                                    ->select('id')
                                    ->first()
                        )->first(),
                        'name' => '이다솔',
                        'student_id' => 2161073,
                        'gender' => '여자',
                        'phone' => [
                            '010-8819-4406'
                        ],
                        'email' => '2161073@pcu.ac.kr',
                        'password' => '2161073@pcu.ac.kr',
                        'address' => '서울특별시 양천구',
                        'birth_date' => '2002-02-17',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => $normalRankId,
                        'name' => '김준석',
                        'student_id' => 1761013,
                        'gender' => '남자',
                        'phone' => [
                            '010-9206-9486'
                        ],
                        'email' => '1761013@pcu.ac.kr',
                        'password' => '1761013@pcu.ac.kr',
                        'address' => '대전 서구',
                        'birth_date' => '1998-03-08',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => $normalRankId,
                        'name' => '이민형',
                        'student_id' => 2033041,
                        'gender' => '여자',
                        'phone' => [
                            '010-2778-7431'
                        ],
                        'email' => 'mi75265@gmail.com',
                        'password' => 'mi75265@gmail.com',
                        'address' => '대전 중구',
                        'birth_date' => '2001-11-14',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => $normalRankId,
                        'name' => '홍민선',
                        'student_id' => 2161057,
                        'gender' => '여자',
                        'phone' => [
                            '010-7527-4800'
                        ],
                        'email' => '2161057@pcu.ac.kr',
                        'password' => '2161057@pcu.ac.kr',
                        'address' => '대전 서구',
                        'birth_date' => '2002-06-18',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => $normalRankId,
                        'name' => '윤성직',
                        'student_id' => 1761034,
                        'gender' => '남자',
                        'phone' => [
                            '010-1234-1234'
                        ],
                        'email' => '1761034@pcu.ac.kr',
                        'password' => '1761034@pcu.ac.kr',
                        'address' => '대전 중구',
                        'birth_date' => '1998-03-05',
                    ],
                    [
                        'club_id' => $club->id,
                        'department_id' => $department->id,
                        'rank_id' => $normalRankId,
                        'name' => '김수진',
                        'student_id' => 2161086,
                        'gender' => '여자',
                        'phone' => [
                            '010-2510-4453'
                        ],
                        'email' => '2161086@pcu.ac.kr',
                        'password' => '2161086@pcu.ac.kr',
                        'address' => '대전 서구',
                        'birth_date' => '2002-02-23',
                    ],
                ];
            } else {
                # C403 동아리가 아니므로 방, 팀장들 선정해줘야함
                $randomEmail = $faker->email;
                $users = [
                    [
                    'club_id' => $club->id,
                    'department_id' => Department::where('club_id', $club->id)
                                                                        ->inRandomOrder()
                                                                        ->value('id'),
                    'rank_id' => Rank::where('club_id', $club->id)
                                                ->where('name', '방장')
                                                ->value('id'),
                    'name' => $faker->name,
                    'student_id' => $faker->numerify('2######'),
                    'gender' => random_int(0, 1) == 0 ? '남자' : '여자',
                    'phone' => [
                        $faker->numerify('010-####-####')
                    ],
                    'email' => $randomEmail,
                    'password' => $randomEmail,
                    'address' => $faker->address,
                    'birth_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    ],
                ];

                $teams = Team::where('club_id', $club->id)
                                        ->where('parent_id', Team::where('club_id', $club->id)
                                                                                    ->whereNull('parent_id')
                                                                                    ->value('id'))
                                        ->get();

                foreach ($teams as $team) {
                    $randomEmail = $faker->email;
                    array_push($users, [
                        'club_id' => $club->id,
                        'department_id' => Department::where('club_id', $club->id)->inRandomOrder()->value('id'),
                        'rank_id' => Rank::where('club_id', $club->id)
                                                    ->where('name', '팀장')
                                                    ->value('id'),
                        'name' => $faker->name,
                        'student_id' => $faker->numerify('2######'),
                        'gender' => random_int(0, 1) == 0 ? '남자' : '여자',
                        'phone' => [
                            $faker->numerify('010-####-####')
                        ],
                        'email' => $randomEmail,
                        'password' => $randomEmail,
                        'address' => $faker->address,
                        'birth_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    ]);
                }
            }

            $normalRankId = Rank::where('club_id', $club->id)
                                                ->where('name', '일반')
                                                ->value('id');
            for($i = 0; $i < 100; $i++) {
                $department = Department::where('club_id', $club->id)->inRandomOrder()->first();
                $randomEmail = $faker->email;
                array_push($users, [
                    'club_id' => $club->id,
                    'department_id' => $department->id,
                    'rank_id' => $normalRankId,
                    'name' => $faker->name,
                    'student_id' => $faker->numerify('2######'),
                    'gender' => random_int(0, 1) == 0 ? '남자' : '여자',
                    'phone' => [
                        $faker->numerify('010-####-####')
                    ],
                    'email' => $randomEmail,
                    'password' => $randomEmail,
                    'address' => $faker->address,
                    'birth_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
                ]);
            }

            # 타임스탬프를 사용하기 위해서 한번에 넣는것이 아닌 반복문을 사용하여 삽입
            foreach ($users as $user) {
                User::create($user);
            }
        }
    }
}
