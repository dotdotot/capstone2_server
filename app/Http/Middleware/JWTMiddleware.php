<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Club;
use App\Models\User;
use App\Models\JwtToken;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # 토큰 추출
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $token = str_replace('Bearer ', '', $authorizationHeader);

        # 토큰 조회
        $tokenInfo = JwtToken::where('access_token', $token)->first();
        if($tokenInfo === null) {
            abort(403, '토큰이 존재하지 않습니다.');
        }

        # 토큰 검사
        $access_token_test = JwtToken::jwtAccessCheckToken($tokenInfo->user_id);
        if($access_token_test === null) {
            abort(401, 'Token Expire');
        }

        #클럽 조회
        $club = Club::where('id', $tokenInfo->club_id)->first();
        if($club === null) {
            abort(403, '토큰에 담긴 정보는 존재하지 않는 동아리입니다.');
        }

        # 사용자 조회
        $user = User::where('id', $tokenInfo->user_id)->first();
        if($user === null) {
            abort(403, '토큰에 담긴 정보는 존재하지 않는 사용자입니다.');
        }

        # request에 정보 담기
        $token_club_id = $tokenInfo->club_id;
        $token_user_id = $tokenInfo->user_id;
        $request->attributes->set('club_id', $token_club_id);
        $request->attributes->set('user_id', $token_user_id);
        return $next($request);
    }
}
