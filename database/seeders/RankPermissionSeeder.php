<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Rank;
use App\Models\RankPermission;

class RankPermissionSeeder extends Seeder
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
            # 방장 권한
            $adminUserPermission = new RankPermission();
            $adminUserPermission->club_id = $club->id;
            $adminUserPermission->rank_id = Rank::where('club_id', $club->id)->where('name', '방장')->value('id');
            $adminUserPermission->board_access = true;
            $adminUserPermission->comment_access = true;
            $adminUserPermission->image_add_access = true;
            $adminUserPermission->anonymous_comment_access = true;
            $adminUserPermission->community_add_access = true;
            $adminUserPermission->user_ben_access = true;
            $adminUserPermission->admin_board_access = true;
            $adminUserPermission->user_change_access = true;
            $adminUserPermission->admin_access = true;
            $adminUserPermission->position = 0;
            $adminUserPermission->save();

            # 팀장 권한
            $teamLeaderPermission = new RankPermission();
            $teamLeaderPermission->club_id = $club->id;
            $teamLeaderPermission->rank_id = Rank::where('club_id', $club->id)->where('name', '팀장')->value('id');
            $teamLeaderPermission->board_access = true;
            $teamLeaderPermission->comment_access = true;
            $teamLeaderPermission->image_add_access = true;
            $teamLeaderPermission->anonymous_comment_access = true;
            $teamLeaderPermission->community_add_access = true;
            $teamLeaderPermission->user_ben_access = false;
            $teamLeaderPermission->admin_board_access = true;
            $teamLeaderPermission->user_change_access = true;
            $teamLeaderPermission->admin_access = false;
            $teamLeaderPermission->position = 1;
            $teamLeaderPermission->save();

            # 일반 사용자 권한
            $normalUserPrmission = new RankPermission();
            $normalUserPrmission->club_id = $club->id;
            $normalUserPrmission->rank_id = Rank::where('club_id', $club->id)->where('name', '일반')->value('id');
            $normalUserPrmission->board_access = true;
            $normalUserPrmission->comment_access = true;
            $normalUserPrmission->image_add_access = true;
            $normalUserPrmission->anonymous_comment_access = true;
            $normalUserPrmission->community_add_access = false;
            $normalUserPrmission->user_ben_access = false;
            $normalUserPrmission->admin_board_access = false;
            $normalUserPrmission->user_change_access = false;
            $normalUserPrmission->admin_access = false;
            $normalUserPrmission->position = 2;
            $normalUserPrmission->save();

            # 명예 사용자 권한
            $honorUserPermission = new RankPermission();
            $honorUserPermission->club_id = $club->id;
            $honorUserPermission->rank_id = Rank::where('club_id', $club->id)->where('name', '명예')->value('id');
            $honorUserPermission->board_access = true;
            $honorUserPermission->comment_access = true;
            $honorUserPermission->image_add_access = false;
            $honorUserPermission->anonymous_comment_access = false;
            $honorUserPermission->community_add_access = false;
            $honorUserPermission->user_ben_access = false;
            $honorUserPermission->admin_board_access = false;
            $honorUserPermission->user_change_access = false;
            $honorUserPermission->admin_access = false;
            $honorUserPermission->position = 3;
            $honorUserPermission->save();
        }
    }
}
