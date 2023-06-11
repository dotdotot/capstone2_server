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
        # 이메일 중복 확인 - api/emailDuplicateCheck?clud_code={clude_code}&email={email}
        Route::get('emailDuplicateCheck', [OpenAccountController::class, 'emailDuplicateCheck']);
        # 학번 중복 확인 - api/studentIdDuplicateCheck?clud_code={clude_code}&student_id={student_id}
        Route::get('studentIdDuplicateCheck', [OpenAccountController::class, 'studentIdDuplicateCheck']);
        # 아이디찾기 - api/idFind?club_code={club_code}&name={name}&email={email}
        Route::get('idFind', [OpenAccountController::class, 'idFind']);
        # 비밀번호찾기 - api/passwordFind?club_code={club_code}&name={name}&student_id={student_id}
        Route::get('passwordFind', [OpenAccountController::class, 'passwordFind']);
        # 토큰 재발급 - api/token?club_id={club_id}&user_id={user_id}
        Route::get('token', [OpenAccountController::class, 'token']);

        Route::prefix('club')->group(function () {
            # 학과 조회 - api/club/departments?club_code={club_code}
            Route::get('departments', [OpenAccountController::class, 'departmentCode']);
        });
    }
);

use App\Http\Controllers\Open\UserController as OpenUserController;
use App\Http\Controllers\Open\ClubController as OpenClubController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Open\OrganizationController as OpenOrganizationController;

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
                # 비상연락망 정보 - api/clubs/{club_id}/users/{user_id}/emergencyContactNetwork
                Route::get('emergencyContactNetwork', [OpenClubController::class, 'emergencyContactNetwork']);

                # 조직도 반환 - api/clubs/{club_id}/users/{user_id}/organizationChat
                Route::get('organizationChart', [OpenOrganizationController::class, 'organizationChart']);

                # 메뉴
                Route::prefix('menu')->group(function () {
                    # 전체 메뉴 리스트 반환 - api/clubs/{club_id}/users/{user_id}/menu
                    Route::get('/', [AdminMenuController::class, 'menus']);
                    # 특정 메뉴 정보 반환 - api/clubs/{club_id}/users/{user_id}/menu/{menu_name}
                    // Route::get('{menu_name}', [AdminMenuController::class, 'menuInformaion']);
                    # 메뉴 추가 - api/clubs/{club_id}/users/{user_id}/menu
                    Route::post('/', [AdminMenuController::class, 'saveMenu']);
                    # 메뉴 수정 - api/clubs/{club_id}/users/{user_id}/menu
                    Route::put('/', [AdminMenuController::class, 'updateMenu']);
                    # 메뉴 삭제 - api/clubs/{club_id}/users/{user_id}/menu
                    Route::delete('/', [AdminMenuController::class, 'deleteMenu']);
                });
            }
        );
    }
);
