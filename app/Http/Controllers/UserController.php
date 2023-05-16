<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * public @method servers(Request $request) :: 사용자가 접속해야 하는 서버정보 조회
 * public @method showProfile(Request $request) :: 프로필 조회
 * public @method editProfile(Request $request) :: 프로필 수정
 * public @method changeCurrentStatus(Request $request) :: 사용자 근무상태 변경
 * public @method connections(Request $request) :: 사용자의 접속 상태 조회
 * public @method options(Request $request) :: 정책 조회
 * public @method editPassword(Request $request) :: 비밀번호 변경
 * public @method devices(Request $request) :: 디바이스 정보 조회
 * public @method logoutDevice(Request $request) :: 디바이스 로그아웃
 * public @method removeDevice(Request $request) :: 디바이스 삭제
 * public @method unreadBox(Request $request) :: 읽지않은 메시지&채팅 존재 조회
 */
class UserController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }
}
