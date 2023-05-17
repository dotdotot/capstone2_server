<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            # 동아리 생성
            $this->call(ClubSeeder::class);

            # 학과 생성(동아리별 학과 존재)
            $this->call(DepartmentSeeder::class);

            # 랭크 생성
            $this->call(RankSeeder::class);

            # 팀 생성
            $this->call(TeamSeeder::class);

            # 사용자 생성
            $this->call(UserSeeder::class);
        }
    }
}
