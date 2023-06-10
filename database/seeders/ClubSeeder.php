<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;

use Faker\Factory as Faker;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');

        # c403 seeder 생성
        Club::create([
            'name' => 'C403',
            'code' => Club::clubCodeCreate(),
            'position' => Club::count(),
            'grade' =>  'normal'
        ]);

        for($i = 0; $i <5; $i++) {
            Club::create([
                'name' => $faker->name,
                'code' => Club::clubCodeCreate(),
                'position' => Club::count(),
                'grade' =>  'normal'
            ]);
        }
    }
}
