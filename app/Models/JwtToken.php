<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

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
    ];

    protected $casts = [
    ];

    protected $fillable = [
        'club_id', 'user_id', 'access_token', 'refresh_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $hidden = [
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->club_id = isset($attributes['club_id']) ? $attributes['club_id'] : null;
        $this->user_id = isset($attributes['user_id']) ? $attributes['user_id'] : null;

        $this->access_token = isset($attributes['access_token']) ? $attributes['access_token'] : null;
        $this->refresh_token = isset($attributes['refresh_token']) ? $attributes['refresh_token'] : null;

        // 삭제/추가/수정 시간
        $this->created_at = isset($attributes['created_at']) ? $attributes['created_at'] : Carbon::now();
        $this->updated_at = isset($attributes['updated_at']) ? $attributes['updated_at'] : Carbon::now();
        $this->deleted_at = isset($attributes['deleted_at']) ? $attributes['deleted_at'] : null;
    }

    # jwt 토큰 발행
    public static function jwtToken($user)
    {
        $issuedAt = time();
        $expiration = $issuedAt + config('jwt.ttl');

        // Generate access token
        $accessTokenPayload = [
            'user_id' => $user->id,
            'exp' => $expiration,
        ];
        $accessToken = JWT::encode($accessTokenPayload, config('jwt.secret'), 'HS256');

        // Generate refresh token
        $refreshExpiration = $issuedAt + config('jwt.refresh_ttl');
        $refreshTokenPayload = [
            'user_id' => $user->id,
            'exp' => $refreshExpiration,
        ];
        $refreshToken = JWT::encode($refreshTokenPayload, config('jwt.secret'), 'HS256');

        return [
            'user_id' => $user->id,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    # jwt 검사
    public static function jwtAccessCheckToken($userId, $access_token)
    {
        # 사용자 검사
        $user = User::find($userId);
        if ($user === null) {
            return null;
        }

        try {
            JWTAuth::setToken($access_token)->authenticate();
        } catch (\Exception $e) {
            return jwtToken($userId);
        }

        return $user;
    }

    public static function jwtRefreshToken($refresh_token)
    {
        $refreshTokenResponse = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => config('passport.password_client_id'),
            'client_secret' => config('passport.password_client_secret'),
            'scope' => '',
        ]);

        if ($refreshTokenResponse->failed()) {
            // Token refresh failed
            return null;
        }

        $refreshedAccessTokenData = $refreshTokenResponse->json();

        return [
            'access_token' => $refreshedAccessTokenData['access_token'],
            'refresh_token' => $refreshedAccessTokenData['refresh_token'],
        ];
    }
}
