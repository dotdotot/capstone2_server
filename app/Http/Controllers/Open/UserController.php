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
        #   접속 기기 알아내기
        return ($request->header('User-Agent'));
    }

    # 최근 생일 반환
    public function recentBirthday(Request $request)
    {
    }
}
