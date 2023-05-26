<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
 * public @method login(Request $request) :: 사용자 로그인
 * public @method joinMembership(Request $request) :: 사용자 회원가입
 * public @method idFind(Request $request) :: 사용자 아이디 찾기
 * public @method passwordFind(Request $request) :: 사용자 비밀번호 찾기
 * public @method refreshtoken(Request $request) :: 토큰 재발급
 */
class UserController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    // login(Request $request) :: 사용자 로그인
    public function login(Request $request)
    {
        # url에서 아이디, 비밀번호 추출
        $id = $request->get('id');
        $password = $request->get('password');

        # 사용자 확인
        $user = User::where('student_id', $id)->first();
        if ($user === null) {
            return abort(403, __('aborts.does_not_match.user_id'));
        }

        # 비밀번호 확인
        if(!User::passwordDecode($user, $password)) {
            return abort(403, __('aborts.does_not_match.password'));
        }

        # 사용자 접속 ip 추가
        UserLogin::create([
            'club_id' => $user->club_id,
            'user_id' => $user->id,
            'ip' => $request->server->get('REMOTE_ADDR'),
        ]);

        # 사용자 최근 접속시간 갱신
        $user->last_login_at = now();

        # 토큰 발급
        $jwtToken = JwtToken::where('user_id', $user->id)->first();
        if($jwtToken === null) {
            # 토큰 자체가 없는 사용자 토큰 발급
            $token = JwtToken::jwtToken($user);

            $jwtToken = new JwtToken();
            $jwtToken->club_id = $user->club_id;
            $jwtToken->user_id = $token['user_id'];
            $jwtToken->access_token = $token['access_token'];
            $jwtToken->refresh_token = $token['refresh_token'];
        } elseif(JwtToken::jwtRefreshToken($jwtToken->refresh_token) === null) {
            # 토큰이 존재하나 refresh_token이 만료된 사용자 토큰 재발급
            $token = JwtToken::jwtToken($user);

            $jwtToken->access_token = $token['access_token'];
            $jwtToken->refresh_token = $token['refresh_token'];
        } else {
            # 액세스 토큰 발급
            $jwtToken->access_token = JwtToken::jwtRefreshToken($jwtToken->refresh_token);
        }
        $jwtToken->save();

        dd($jwtToken);
    }

    // joinMembership(Request $request) :: 사용자 회원가입
    public function joinMembership(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'nullable|integer|min:1',
        ], [
            '*' => __('validations.format')
        ]);

        $club = $request->get('club_id');
        $user = $request->get('user_id');

        $lastTimestampMs = $request->input('last_timestamp_ms') === null ? null : intval($request->input('last_timestamp_ms'));
    }

    // idFind(Request $request) :: 사용자 아이디 찾기
    public function idFind(Request $request)
    {
        $type = $request->input('type');
        if ($type === null) {
            abort(403, __('aborts.request'));
        }
    }

    // refreshtoken(Request $request) :: 토큰 재발급
    public function refreshtoken(Request $request)
    {
    }

    // passwordFind(Request $request) :: 사용자 비밀번호 찾기
    public function passwordFind(Request $request)
    {
    }
}
