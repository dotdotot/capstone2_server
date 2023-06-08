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
            abort(403, 'aborts.does_not_exist.token');
        }

        #클럽 조회
        $club = Club::where('id', $tokenInfo->club_id)->first();
        if($club === null) {
            abort(403, 'aborts.does_not_exist.club_id');
        }

        # 사용자 조회
        $user = User::where('id', $tokenInfo->user_id)->first();
        if($user === null) {
            abort(403, 'aborts.does_not_exist.user_id');
        }

        # request에 정보 담기
        $token_club_id = $tokenInfo->club_id;
        $token_user_id = $tokenInfo->user_id;
        $request->attributes->set('token_club_id', $token_club_id);
        $request->attributes->set('token_user_id', $token_user_id);
        return $next($request);
    }
}
