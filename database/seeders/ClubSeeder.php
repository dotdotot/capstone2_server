<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Club;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # c403 seeder 생성
        Club::create([
            'name' => 'C403',
            'code' => Club::clubCodeCreate(),
            'position' => Club::count(),
            'grade' =>  'normal'
        ]);
    }
}
