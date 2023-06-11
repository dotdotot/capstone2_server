<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;
use App\Models\User;
use App\Models\Rank;
use App\Models\ClubEmergencyContactNetwork;

use Faker\Factory as Faker;

class ClubEmergencyContactNetworkSeeder extends Seeder
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

        # 클럽별로 생성
        foreach ($clubs as $club) {
            if($club->name === 'C403') {
                ClubEmergencyContactNetwork::create([
                    'club_id' => $club->id,
                    'email' => '2161045@pcu.ac.kr',
                    'phone' => ['010-4043-0557'],
                    'location' => '배재대학교 정보과학관 C403',
                ]);
            } else {
                $leaderUser = User::where('club_id', $club->id)
                                                ->where('rank_id', Rank::where('club_id', $club->id)
                                                                                        ->where('name', '방장')
                                                                                        ->value('id'))
                                                ->first();
                ClubEmergencyContactNetwork::create([
                    'club_id' => $club->id,
                    'email' => $leaderUser->email,
                    'phone' => $leaderUser->phone,
                    'location' => $faker->numerify('배재대학교 정보과학관 C###')
                ]);
            }
        }
    }
}
