<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\User;
use App\Models\CCTVConsent;

class CCTVConsentSeeder extends Seeder
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
                $cctvConsent = new CCTVConsent();
                $cctvConsent->club_id = $club->id;
                $cctvConsent->user_id = $user->id;
                $cctvConsent->consent = true;
                $cctvConsent->save();
            });
        }
    }
}
