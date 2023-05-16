<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Department;

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
        Department::create([
            'club_id' => $club->id,
            'name' => '컴퓨터공학과',
            'code' => Club::clubCodeCreate(),
            'position' => Department::where('club_id', $club->id)->count(),
        ]);
    }
}
