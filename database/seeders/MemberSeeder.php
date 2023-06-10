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
        $clubs = Club::get();
        foreach ($clubs as $club) {
            if($club->name === 'C403') {
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
                    ->whereNotIn('rank_id', [1,2])
                    ->get()
                    ->each(function ($user) use ($club) {
                        $member = new Member();
                        $member->club_id = $club->id;
                        $member->user_id = $user->id;
                        $member->department_id = $user->department_id;
                        $member->rank_id = $user->rank_id;
                        if(in_array($user->name, ['이승주', '윤성직', '유성훈', '장우철', '이민형', '김수진', '노혜민', '황수진', '김준석', '서정찬', '홍민선'])) {
                            $member->team_id = Team::where('name', '컴온')->first()->id;
                            $member->position = Member::where('team_id', Team::where('name', '컴온')->first()->id)->count();
                        } elseif(in_array('name', ['한다영', '안노아', '서아영', '나승주', '김시연'])) {
                            $member->team_id = Team::where('name', '룩')->first()->id;
                            $member->position = Member::where('team_id', Team::where('name', '룩')->first()->id)->count();
                        } else {
                            $randomTeamId = Team::whereNotIn('name', ['C403','컴온','룩'])->inRandomOrder()->first()->id;
                            $member->team_id = $randomTeamId;
                            $member->position = Member::where('team_id', $randomTeamId)->count();
                        }
                        $member->default = true;
                        $member->leader = false;
                        $member->save();
                    });
            } else {
                # 방장 추가
                $leaderTeam = Team::where('club_id', $club->id)
                                                    ->whereNull('parent_id')
                                                    ->first();
                $leaderUser = User::where('club_id', $club->id)
                                                ->where('rank_id', Rank::where('club_id', $club->id)
                                                                                        ->where('name', '방장')
                                                                                        ->value('id'))
                                                ->first();
                Member::create([
                    'club_id' => $club->id,
                    'department_id' => $leaderUser->department_id,
                    'team_id' => $leaderTeam->id,
                    'user_id' => $leaderUser->id,
                    'rank_id' => $leaderUser->rank_id,
                    'default' => true,
                    'leader' => true,
                    'position' => 0
                ]);

                # 팀장 추가
                $teams = Team::where('club_id', $club->id)
                                        ->whereIn('parent_id', [null, Team::where('club_id', $club->id)
                                                                                    ->whereNull('parent_id')
                                                                                    ->value('id')])
                                        ->get();

                $users = User::where('club_id', $club->id)
                                        ->where('rank_id', Rank::where('club_id', $club->id)
                                                                                    ->where('name', '팀장')
                                                                                    ->value('id'))
                                        ->get();
                foreach ($teams as $key => $team) {
                    $user = $users[$key];

                    Member::create([
                        'club_id' => $club->id,
                        'department_id' => $user->department_id,
                        'team_id' => $team->id,
                        'user_id' => $user->id,
                        'rank_id' => $user->rank_id,
                        'default' => true,
                        'leader' => true,
                        'position' => 0
                    ]);
                }

                User::where('club_id', $club->id)
                    ->whereNotIn('rank_id', Rank::where('club_id', $club->id)
                                                                    ->whereIn('name', ['방장', '팀장'])
                                                                    ->select('id')
                                                                    ->get()
                                                                    ->pluck('id')
                                                                    ->toArray())
                    ->get()
                    ->each(function ($user) use ($club) {
                        $member = new Member();
                        $member->club_id = $club->id;
                        $member->user_id = $user->id;
                        $member->department_id = $user->department_id;
                        $member->rank_id = $user->rank_id;

                        $member->team_id = Rank::where('club_id', $club->id)
                                                                    ->InRandomOrder()
                                                                    ->value('id');
                        $member->position = Member::where('team_id', $member->team_id)->count();

                        $member->default = true;
                        $member->leader = false;
                        $member->save();
                    });
            }
        }
    }
}
