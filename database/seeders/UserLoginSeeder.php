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
        $clubs = Club::select('id', 'name')->get();

        foreach ($clubs as $club) {
            for($i = 0; $i < 300; $i++) {
                # 랜덤 사용자 추출
                $user = User::InRandomOrder()->select(['id'])->first();

                # 해당 사용자로 접속
                $userLogin = new UserLogin();
                $userLogin->club_id = $club->id;
                $userLogin->user_id = $user->id;
                $userLogin->ip = $faker->ipv4;

                $random_number = rand(0, 4);
                if($random_number === 0) {
                    $userLogin->device = "Windows";
                } elseif($random_number === 1) {
                    $userLogin->device = "Mac OS";
                } elseif($random_number === 2) {
                    $userLogin->device = "Android";
                } elseif($random_number === 3) {
                    $userLogin->device = "Linux";
                } else {
                    $userLogin->device = "Test";
                }
                $userLogin->save();

                $user->last_login_at = now();
                $user->save();
            }
        }
    }
}
