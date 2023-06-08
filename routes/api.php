<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Open\AccountController as OpenAccountController;

# 로그인 전 사용 가능한 APIs
Route::group(
    [],
    function () {
        # 로그인 - api/login?id={id}&password={password}
        Route::get('login', [OpenAccountController::class, 'login']);
        # 회원가입 - api/joinMembership
        Route::post('joinMembership', [OpenAccountController::class, 'joinMembership']);
        # 아이디찾기 - api/idFind?club_code={club_code}&name={name}&email={email}
        Route::get('idFind', [OpenAccountController::class, 'idFind']);
        # 비밀번호찾기 - api/passwordFind?club_code={club_code}&name={name}&student_id={student_id}
        Route::get('passwordFind', [OpenAccountController::class, 'passwordFind']);
        # 토큰 재발급 - api/token?club_code={club_code}&user_id={user_id}
        Route::get('token', [OpenAccountController::class, 'token']);

        Route::prefix('club')->group(function () {
            # 학과 조회 - api/club/departments?club_code={club_code}
            Route::get('departments', [OpenAccountController::class, 'departmentCode']);
        });
    }
);

use App\Http\Controllers\Open\UserController as OpenUserController;
use App\Http\Controllers\Open\ClubController as OpenClubController;

# 로그인 후 사용 가능한 APIs
Route::group(
    [
        'prefix' => 'clubs/{club_id}/users/{user_id}',
        'where' => [
            'club_id' => '[0-9]+',
            'user_id' => '[0-9]+',
        ],
    ],
    function () {
        /**
        * 미들웨어: jwt 적용
        */
        Route::middleware(['jwt'])->group(
            function () {
                # 최근 생일 - api/clubs/{club_id}/users/{user_id}/recentBirthday
                Route::get('recentBirthday', [OpenUserController::class, 'recentBirthday']);
                # 최근 로그인 정보 - api/clubs/{club_id}/users/{user_id}/loginInfomation
                Route::get('loginInfomation', [OpenUserController::class, 'loginInfomation']);
            }
        );
    }
);
