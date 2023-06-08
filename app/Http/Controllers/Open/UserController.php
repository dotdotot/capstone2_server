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

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * public @method loginInfomation(Request $request) :: 로그인 정보 반환
 * public @method recentBirthday(Request $request) :: 최근 생일 반환
 */
class UserController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    # 로그인 정보 반환
    public function loginInfomation(Request $request)
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

        # 사용자 접속 정보 추출(최대 3)
        $userConnectInfos = UserLogin::where('club_id', $club->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->select(['ip', 'device', 'created_at'])
            ->get()
            ->toArray();

        return $userConnectInfos;
    }

    # 최근 생일 반환
    public function recentBirthday(Request $request)
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

        # 생일 추출
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;
        $birthdays = User::with('rank:id,name')
            ->where('club_id', $club->id)
            ->whereMonth('birth_date', $currentMonth)
            ->orderBy(DB::raw("DATE_PART('day', birth_date) >= $currentDay desc, DATE_PART('day', birth_date)"))
            ->limit(3)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user['name'],
                    'birth_date' => $user['birth_date'],
                    'rank' => $user['rank']['name'],
                ];
            });

        return $birthdays;
    }
}
