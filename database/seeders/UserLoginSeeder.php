<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\Models\Club;
use App\Models\User;
use App\Models\Department;
use App\Models\UserLogin;

class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $club = Club::where('name', 'C403')->select('id', 'name')->first();

        for($i = 0; $i < 300; $i++) {
            # 랜덤 사용자 추출
            $user = User::InRandomOrder()->select(['id'])->first();

            # 해당 사용자로 접속
            $userLogin = new UserLogin();
            $userLogin->club_id = $club->id;
            $userLogin->user_id = $user->id;
            $userLogin->ip = $faker->ipv4;
            $userLogin->save();

            $user->last_login_at = now();
            $user->save();
        }
    }
}
