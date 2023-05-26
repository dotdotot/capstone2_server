<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Department;

use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = Club::where('name', 'C403')->select('id', 'name')->first();
        $faker = Faker::create('ko_KR');

        Department::create([
            'club_id' => $club->id,
            'name' => '컴퓨터공학과',
            'code' => Department::departmentCodeCreate(),
            'position' => Department::where('club_id', $club->id)->count(),
        ]);

        # faker를 사용하여 10개의 학과 생성
        for($i = 0; $i < 10; $i++) {
            $departmentName = $faker->numerify('테스트학과 ##');
            if(Department::where('name', $departmentName)->first() !== null) {
                $i--;
                continue;
            }

            Department::create([
                'club_id' => $club->id,
                'name' => $departmentName,
                'code' => Department::departmentCodeCreate(),
                'position' => Department::where('club_id', $club->id)->count(),
            ]);
        }
    }
}
