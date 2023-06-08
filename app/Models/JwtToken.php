<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

/**
 * public @method departments()
 */
class JwtToken extends BaseModel
{
    use HybridRelations;
    use SoftDeletes;

    protected $connection = 'pgsql';
    protected $table = 'jwt_token';

    protected $dates = [
        'access_token_end_at', 'refresh_token_end_at', 'deleted_at'
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'user_id', 'access_token', 'access_token_end_at', 'refresh_token', 'refresh_token_end_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->user_id = isset($attributes['user_id']) ? $attributes['user_id'] : null;

        $this->access_token = isset($attributes['access_token']) ? $attributes['access_token'] : null;
        $this->access_token_end_at = isset($attributes['access_token_end_at']) ? $attributes['access_token_end_at'] : null;
        $this->refresh_token = isset($attributes['refresh_token']) ? $attributes['refresh_token'] : null;
        $this->refresh_token_end_at = isset($attributes['refresh_token_end_at']) ? $attributes['refresh_token_end_at'] : null;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    # jwt 토큰 발행
    public static function jwtToken($userId)
    {
        $issuedAt = time();
        $expiration = $issuedAt + config('jwt.ttl');
        $key = config('jwt.secret');

        // Generate access token
        $accessTokenPayload = [
            'user_id' => $userId,
            'exp' => $expiration,
        ];
        $accessToken = JWT::encode($accessTokenPayload, config('jwt.secret'), 'HS256');
        $accessTokenEndAt = null;
        try {
            $accessTokenEndAt = JWT::decode($accessToken, new Key($key, 'HS256'));
            $accessTokenEndAt = $accessTokenEndAt->exp;
            $accessTokenEndAt = date('Y-m-d H:i:s', $accessTokenEndAt);
        } catch(\Exception $e) {
            return null;
        }

        // Generate refresh token
        $refreshExpiration = $issuedAt + config('jwt.refresh_ttl');
        $refreshTokenPayload = [
            'user_id' => $userId,
            'exp' => $refreshExpiration,
        ];
        $refreshToken = JWT::encode($refreshTokenPayload, config('jwt.secret'), 'HS256');
        $refreshTokenEndAt = null;
        try {
            $refreshTokenEndAt = JWT::decode($refreshToken, new Key($key, 'HS256'));
            $refreshTokenEndAt = $refreshTokenEndAt->exp;
            $refreshTokenEndAt = date('Y-m-d H:i:s', $refreshTokenEndAt);
        } catch(\Exception $e) {
            return null;
        }

        return [
            'user_id' => $userId,
            'access_token' => $accessToken,
            'access_token_end_at' => $accessTokenEndAt,
            'refresh_token' => $refreshToken,
            'refresh_token_end_at' => $refreshTokenEndAt
        ];
    }

    # jwt accessToken 검사
    public static function jwtAccessCheckToken($userId)
    {
        # 토큰 유효성 검사
        $token = JwtToken::where('user_id', $userId)->first();
        if ($token === null || $token->access_token_end_at->isPast()) {
            return null;
        }

        return 'success';
    }

    # 액세스 토큰 재발급, jwt refresh token 검사
    public static function jwtRefreshToken($userId)
    {
        # 토큰 유효성 검사
        $refreshTokenEndAt = JwtToken::where('user_id', $userId)->value('refresh_token_end_at');
        if ($refreshTokenEndAt->isPast()) {
            return null;
        }

        $issuedAt = time();
        $expiration = $issuedAt + config('jwt.ttl');
        $key = config('jwt.secret');

        // Generate access token
        $accessTokenPayload = [
            'user_id' => $userId,
            'exp' => $expiration,
        ];
        $accessToken = JWT::encode($accessTokenPayload, config('jwt.secret'), 'HS256');
        $accessTokenEndAt = null;
        try {
            $accessTokenEndAt = JWT::decode($accessToken, new Key($key, 'HS256'));
            $accessTokenEndAt = $accessTokenEndAt->exp;
            $accessTokenEndAt = date('Y-m-d H:i:s', $accessTokenEndAt);
        } catch(\Exception $e) {
            return null;
        }

        return [
            'user_id' => $userId,
            'access_token' => $accessToken,
            'access_token_end_at' => $accessTokenEndAt
        ];
    }
}
