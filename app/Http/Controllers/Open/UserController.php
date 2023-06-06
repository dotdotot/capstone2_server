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
}
