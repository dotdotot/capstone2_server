<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Team;

use Faker\Factory as Faker;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = Club::where('name', 'C403')->first();
        $faker = Faker::create('ko_KR');
        $teamCount = 0;

        // 최상위 팀 생성
        $topTeam = Team::create([
            'club_id' => $club->id,
            'parent_id' => null,
            'name' => 'C403',
            'position' => $teamCount++,
            'path' => 'C403',
        ]);

        // 하위 팀 생성
        Team::create([
            'club_id' => $club->id,
            'parent_id' => $topTeam->id,
            'name' => '룩',
            'position' => $teamCount++,
            'path' => $topTeam->name . ' -> ' . '룩',
        ]);

        Team::create([
            'club_id' => $club->id,
            'parent_id' => $topTeam->id,
            'name' => '컴온',
            'position' => $teamCount++,
            'path' => $topTeam->name . ' -> ' . '컴온'
        ]);

        # faker를 사용하여 랜덤 팀 10개 생성
        for($i = 0; $i < 10; $i++) {
            $randomTeam = Team::whereNotNull('parent_id')->inRandomOrder()->first();
            $fakerName = $faker->numerify('테스트팀 ###');
            if(Team::where('name', $fakerName)->first() !== null) {
                $i--;
                continue;
            }

            Team::create([
                'club_id' => $club->id,
                'parent_id' => $randomTeam->id,
                'name' => $fakerName,
                'position' => $teamCount++,
                'path' => $randomTeam->path . ' -> ' . $fakerName,
            ]);
        }
    }
}
