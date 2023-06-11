<?php

namespace App\Http\Controllers\Open;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Department;
use App\Models\JwtToken;
use App\Models\Member;
use App\Models\Rank;
use App\Models\RankPermission;
use App\Models\Team;
use App\Models\TeamClosure;
use App\Models\User;
use App\Models\UserLogin;

/**
 * public @method organizationChart(Request $request) :: 조직도 반환
 */
class OrganizationController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    public function organizationChart(Request $request)
    {
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }

        # 모든 부서 조회
        $departments = Team::with([
            'members.user:id,name',
            'closureDescendants'
        ])
            ->where('club_id', $club->id)
            ->orderByRaw('path collate "C"')
            ->get()
            ->map(function ($team, $index) {
                // $team->type = 'team';

                $childrenTeams = $team->closureDescendants->pluck('descendant');
                # 하위부서 포함 사용자 수
                $team->number_of_user = Member::whereIn('team_id', $childrenTeams->toArray())->count();
                # 해당 부서 사용자 수
                $team->number_of_this_user = $team->members->where('default', true)->count();
                # 하위부서의 id
                $team->children_teams = $childrenTeams->diff([$team->id])->flatten()->toArray();
                # 해당 부서의 사용자
                $team->users = ($team->members->pluck('user.name')->toArray());
                # 최상위 부서는 회사
                if ($team->parent_id === null) {
                    $team->type = 'team';
                    $team->position = 0;
                }
                if ($index === 0) {
                    $team->parent_id = null;
                }

                return $team->only([
                    'id', 'parent_id', 'name', 'path', 'users'
                ]);
            });

        return $departments;
    }
}
