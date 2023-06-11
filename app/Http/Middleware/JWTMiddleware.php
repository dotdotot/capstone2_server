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

        $urlSegments = explode('/', $request->getPathInfo());
        $club_id = $urlSegments[3];
        $user_id = $urlSegments[5];

        # 토큰 조회
        $tokenInfo = JwtToken::where('access_token', $token)->first();
        if($tokenInfo === null) {
            abort(403, 'Token does not exist.');
        }

        # 본인 토큰인지 검사
        $userToken = JwtToken::where('club_id', $club_id)
                                                ->where('user_id', $user_id)
                                                ->where('access_token', $token)
                                                ->first();
        if($userToken === null) {
            abort(403, 'Another user token is in use.');
        }

        # 토큰 검사
        $access_token_test = JwtToken::jwtAccessCheckToken($tokenInfo->user_id);
        if($access_token_test === null) {
            abort(401, 'Token Expire');
        }

        #클럽 조회
        $club = Club::where('id', $tokenInfo->club_id)->first();
        if($club === null) {
            abort(403, 'The information in the token is a club that does not exist.');
        }

        # 사용자 조회
        $user = User::where('id', $tokenInfo->user_id)->first();
        if($user === null) {
            abort(403, 'The information contained in the token is a non-existent user.');
        }

        # request에 정보 담기
        $token_club_id = $tokenInfo->club_id;
        $token_user_id = $tokenInfo->user_id;
        $request->attributes->set('club_id', $token_club_id);
        $request->attributes->set('user_id', $token_user_id);
        return $next($request);
    }
}
