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
        $clubs = Club::get();

        foreach($clubs as $club) {
            $ranks = [
                [
                    'club_id' => $club->id,
                    'name' => '방장',
                    'position' => Rank::where('club_id', $club->id)->count(),
                ],
                [
                    'club_id' => $club->id,
                    'name' => '팀장',
                    'position' => Rank::where('club_id', $club->id)->count(),
                ],
                [
                    'club_id' => $club->id,
                    'name' => '일반',
                    'position' => Rank::where('club_id', $club->id)->count(),
                ],
                [
                    'club_id' => $club->id,
                    'name' => '명예',
                    'position' => Rank::where('club_id', $club->id)->count(),
                ]
            ];

            foreach ($ranks as $rank) {
                Rank::create($rank);
            }
        }
    }
}
