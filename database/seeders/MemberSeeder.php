<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Club;
use App\Models\Member;
use App\Models\Rank;
use App\Models\User;
use App\Models\Department;
use App\Models\Team;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $club = Club::where('name', 'C403')->first();
        $department = Department::where('name', '컴퓨터공학과')->first();
        $c403User = User::where('name', '차정준')->first();
        $c403Team = Team::where('name', 'C403')->first();
        $c403Member = new Member();
        $c403Member->club_id = $club->id;
        $c403Member->department_id = $department->id;
        $c403Member->user_id = $c403User->id;
        $c403Member->rank_id = $c403User->rank_id;
        $c403Member->team_id = $c403Team->id;
        $c403Member->default = true;
        $c403Member->leader = true;
        $c403Member->save();

        $comeonMember = new Member();
        $comeonMember->club_id = $club->id;
        $comeonMember->department_id = $department->id;
        $comeonMember->user_id = $c403User->id;
        $comeonMember->rank_id = $c403User->rank_id;
        $comeonMember->team_id = Team::where('name', '컴온')->first()->id;
        $comeonMember->default = false;
        $comeonMember->leader = false;
        $comeonMember->save();

        $lookMember = new Member();
        $lookMember->club_id = $club->id;
        $lookMember->department_id = $department->id;
        $lookMember->user_id = $c403User->id;
        $lookMember->rank_id = $c403User->rank_id;
        $lookMember->team_id = Team::where('name', '룩')->first()->id;
        $lookMember->default = false;
        $lookMember->leader = false;
        $lookMember->save();

        $lookUser = User::where('name', '임준형')->first();
        $lookTeam = Team::where('name', '컴온')->first();
        $lookLeaderMember = new Member();
        $lookLeaderMember->club_id = $club->id;
        $lookLeaderMember->department_id = $department->id;
        $lookLeaderMember->user_id = $lookUser->id;
        $lookLeaderMember->rank_id = $lookUser->rank_id;
        $lookLeaderMember->position = Member::where('team_id', $lookTeam->id)->count();
        $lookLeaderMember->team_id = $lookTeam->id;
        $lookLeaderMember->default = true;
        $lookLeaderMember->leader = true;
        $lookLeaderMember->save();

        $comeonUser = User::where('name', '이다솔')->first();
        $comeonTeam = Team::where('name', '룩')->first();
        $comonLeaderMember = new Member();
        $comonLeaderMember->club_id = $club->id;
        $comonLeaderMember->department_id = $department->id;
        $comonLeaderMember->user_id = $comeonUser->id;
        $comonLeaderMember->rank_id = $comeonUser->rank_id;
        $comonLeaderMember->position = Member::where('team_id', $comeonTeam->id)->count();
        $comonLeaderMember->team_id = $comeonTeam->id;
        $comonLeaderMember->default = true;
        $comonLeaderMember->leader = true;
        $comonLeaderMember->save();

        User::where('club_id', $club->id)
                ->where('department_id', $department->id)
                ->whereNotIn('rank_id', [1,2])
                ->get()
                ->each(function ($user) use ($club, $department) {
                    $member = new Member();
                    $member->club_id = $club->id;
                    $member->department_id = $department->id;
                    $member->user_id = $user->id;
                    $member->rank_id = $user->rank_id;
                    if($user->whereIn('name', ['이승주', '윤성직', '유성훈', '장우철', '이민형', '김수진', '노혜민', '황수진', '김준석', '서정찬', '홍민선'])->get()->isNotEmpty()) {
                        $member->team_id = Team::where('name', '컴온')->first()->id;
                        $member->position = Member::where('team_id', Team::where('name', '컴온')->first()->id)->count();
                    } elseif($user->whereIn('name', ['한다영', '안노아', '서아영', '나승주', '김시연'])->get()->isNotEmpty()) {
                        $member->team_id = Team::where('name', '룩')->first()->id;
                        $member->position = Member::where('team_id', Team::where('name', '룩')->first()->id)->count();
                    } else {
                        $member->team_id = null;
                    }
                    $member->default = true;
                    $member->leader = false;
                    $member->save();
                });
    }
}
