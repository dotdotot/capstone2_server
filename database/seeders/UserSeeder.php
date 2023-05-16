<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = Club::where('name', 'C403')->select('id', 'name')->first();
        $department = Department::where('club_id', $club->id)->first();

        User::create([
            [
                'club_id' => $club->id,
                'department_id' => $department->id,
                'rank_id' => null,
                'name' => '차정준',
                'student_id' => 2161045,
                'gender' => 'men',
                'phone' => [
                    '010-4043-0557'
                ],
                'email' => '2161045@pcu.ac.kr',
                'address' => '대전 서구',
                'birth_date' => DateTime::createFromFormat('Ymd', '19980308')
            ]
        ]);
    }
}
