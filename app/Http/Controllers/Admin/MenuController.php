<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\Club;
use App\Models\CommonMoney;
use App\Models\Department;
use App\Models\ImageBoard;
use App\Models\JwtToken;
use App\Models\Member;
use App\Models\Rank;
use App\Models\RankPermission;
use App\Models\Team;
use App\Models\TeamClosure;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Menu;

/**
 * public @method menus(Request $request) :: 메뉴 반환
 */
class MenuController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    # menus(Request $request) :: 메뉴 반환
    public function menus(Request $request)
    {
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }

        # 메뉴 추출
        $menu = Menu::where('club_id', $club->id)
                                ->select(['id', 'title', 'type'])
                                ->get()
                                ->toArray();
        if($menu === null) {
            return abort(403, __('aborts.club_doex_not_exist.menu'));
        }

        return $menu;
    }

    # menuInformaion(Request $request) :: 메뉴 상세 정보
    public function menuInformaion(Request $request)
    {
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }
    }

    # saveMenu(Request $request) :: 메뉴 추가
    public function saveMenu(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:board,bulletion,image_board'
        ], [
            '*' => __('validations.format')
        ]);

        $menu_title = $request->input('title');
        $menu_type = $request->input('type');
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }

        # 사용자 권한 조회
        $admin_permission = RankPermission::where('club_id', $club->id)
                                                        ->where('rank_id', $user->rank_id)
                                                        ->value('admin_access');
        if($admin_permission === null || $admin_permission === false) {
            return abort(403, __('aborts.club_doex_not_exist.permission'));
        }

        # 메뉴 생성
        $menu = Menu::create([
            'club_id' => $club->id,
            'title' => $menu_title,
            'type' => $menu_type,
            'position' => Menu::where('club_id', $club->id)->count(),
        ]);
        if($menu->type === 'image_board') {
            CommonMoney::create([
                'club_id' => $club->id,
                'menu_id' => $menu->id,
                'money' => 0,
                'postion' => CommonMoney::where('club_id', $club->id)->count()
            ]);
        }

        return response()->json([
            'result' => 'success'
        ], 201);
    }

    # updateMenu(Request $request) :: 메뉴 수정
    public function updateMenu(Request $request)
    {
        $this->validate($request, [
            'menu_id' => 'required|integer',
            'title' => 'required|string|max:255'
        ], [
            '*' => __('validations.format')
        ]);

        $menu_id = intval($request->input('menu_id'));
        $menu_title = $request->input('title');
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }

        # 사용자 권한 조회
        $admin_permission = RankPermission::where('club_id', $club->id)
                                                        ->where('rank_id', $user->rank_id)
                                                        ->value('admin_access');
        if($admin_permission === null || $admin_permission === false) {
            return abort(403, __('aborts.club_doex_not_exist.permission'));
        }

        # 메뉴 업데이트
        $menu = Menu::where('club_id', $club->id)->where('id', $menu_id)->first();
        if($menu === null) {
            return abort(403, __('aborts.club_doex_not_exist.menu'));
        }
        $menu->title = $menu_title;
        $menu->save();

        return response()->json([
            'result' => 'success'
        ], 201);
    }

    # deleteMenu(Request $request) :: 메뉴 삭제
    public function deleteMenu(Request $request)
    {
        $this->validate($request, [
            'menu_id' => 'required|integer',
        ], [
            '*' => __('validations.format')
        ]);

        $menu_id = intval($request->input('menu_id'));
        # 아이디, 비밀번호 추출
        $club_id = intval($request->get('club_id'));
        $user_id = intval($request->get('user_id'));

        # 클럽 추출
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 추출
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            return abort(403, __('aborts.does_not_exist.user_id'));
        }

        # 사용자 권한 조회
        $admin_permission = RankPermission::where('club_id', $club->id)
                                                        ->where('rank_id', $user->rank_id)
                                                        ->value('admin_access');
        if($admin_permission === null || $admin_permission === false) {
            return abort(403, __('aborts.club_doex_not_exist.permission'));
        }

        # 메뉴 삭제
        $menu = Menu::where('club_id', $club->id)->where('id', $menu_id)->first();
        if($menu === null) {
            return abort(403, __('aborts.club_doex_not_exist.menu'));
        }
        $menu->delete();

        # 메뉴를 외래키로 가지고 있는 다른 튜플들도 삭제
        if($menu->type === '') {
            $borads = Board::where('club_id', $club->id)
                    ->where('menu_id', $menu->id)
                    ->get();
            foreach($borads as $borad) {
                $borad->delete();
            }
        } elseif($menu->type === '') {
            $bulletins = Bulletin::where('club_id', $club->id)
                    ->where('menu_id', $menu->id)
                    ->get();
            foreach($bulletins as $bulletin) {
                $bulletin->delete();
            }
        } elseif($menu->type === '') {
            $imageBoards = ImageBoard::where('club_id', $club->id)
                    ->where('menu_id', $menu->id)
                    ->get();
            foreach($imageBoards as $imageBoard) {
                $imageBoard->delete();
            }
            $commonMoneys = CommonMoney::where('club_id', $club->id)
                                    ->where('menu_id', $menu->id)
                                    ->get();
            foreach($commonMoneys as $commonMoney) {
                $commonMoney->delete();
            }
            # 이미지 예외처리 추가해야함
        }

        return response()->json([
            'result' => 'success'
        ], 201);
    }
}
