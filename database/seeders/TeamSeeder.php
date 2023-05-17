<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Team;

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

        // 최상위 팀 생성
        $topTeam = Team::create([
            'club_id' => $club->id,
            'parent_id' => null,
            'name' => 'C403',
            'position' => Team::where('club_id', $club->id)->count(),
            'path' => 'C403',
        ]);

        // 하위 팀 생성
        Team::create([
            'club_id' => $club->id,
            'parent_id' => $topTeam->id,
            'name' => '룩',
            'position' => Team::where('club_id', $club->id)->count(),
            'path' => $topTeam->name . ' -> ' . '룩',
        ]);

        Team::create([
            'club_id' => $club->id,
            'parent_id' => $topTeam->id,
            'name' => '컴온',
            'position' => Team::where('club_id', $club->id)->count(),
            'path' => $topTeam->name . ' -> ' . '컴온'
        ]);
    }
}
