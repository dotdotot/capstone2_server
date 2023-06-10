<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ProjectConsent;
use App\Models\Club;
use App\Models\User;

class ProjectConsentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clubs = Club::get();
        foreach ($clubs as $club) {
            User::where('club_id', $club->id)->each(function ($user) use ($club) {
                $projectConsent = new ProjectConsent();
                $projectConsent->club_id = $club->id;
                $projectConsent->user_id = $user->id;
                $projectConsent->consent = true;
                $projectConsent->save();
            });
        }
    }
}
