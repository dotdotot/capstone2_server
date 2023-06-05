<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Open\AccountController as AccountController;

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

# 로그인 전 사용 가능한 APIs
Route::group(
    [],
    function () {
        # 로그인 - api/login?id={id}&password={password}
        Route::get('login', [AccountController::class, 'login']);
        # 회원가입 - api/joinMembership
        Route::post('joinMembership', [AccountController::class, 'joinMembership']);
        # 아이디찾기 - api/idFind?club_code={club_code}&name={name}&email={email}
        Route::get('idFind', [AccountController::class, 'idFind']);
        # 비밀번호찾기 - api/passwordFind?club_code={club_code}&name={name}&student_id={student_id}
        Route::get('passwordFind', [AccountController::class, 'passwordFind']);
        # 토큰 재발급 - api/token?club_code={club_code}&user_id={user_id}
        Route::get('token', [AccountController::class, 'token']);

        Route::prefix('club')->group(function () {
            # 학과 조회 - api/club/departments?club_code={club_code}
            Route::get('departments', [AccountController::class, 'departmentCode']);
        });
    }
);

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
        Route::middleware(['jwt'/* , 'contract' */])->group(
            function () {
                # 클라이언트 버전 정보 - api/v1/companies/{company_id}/users/{user_id}/client-versions?agent_id={agent_id}
                Route::get('client-versions', [CommonAgentController::class, 'clientVersion']);

                # 클라이언트 버전 정보 - api/v1/companies/{company_id}/users/{user_id}/client-versions?agent_id={agent_id}
                Route::get('client-versions', [CommonAgentController::class, 'clientVersion']);
            }
        );
    }
);
