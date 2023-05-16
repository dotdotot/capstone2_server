<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Rank;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = Club::where('name', 'C403')->first();
        $rankCount = Rank::where('club_id', $club->id)->count();
        $ranks = [
            [
                'club_id' => $club->id,
                'name' => '방장',
                'position' => $rankCount++,
            ],
            [
                'club_id' => $club->id,
                'name' => '팀장',
                'position' => $rankCount++,
            ],
            [
                'club_id' => $club->id,
                'name' => '일반',
                'position' => $rankCount++,
            ],
            [
                'club_id' => $club->id,
                'name' => '명예',
                'position' => $rankCount++,
            ]
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}
