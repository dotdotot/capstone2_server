<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Models\Club;
use App\Models\Department;
use App\Models\JwtToken;
use App\Models\Member;
use App\Models\Rank;
use App\Models\RankPermission;
use App\Models\Team;
use App\Models\TeamClosure;
use App\Models\User;
use App\Models\UserLogin;

/**
 * public @method login(Request $request) :: 사용자 로그인
 * public @method joinMembership(Request $request) :: 사용자 회원가입
 * public @method idFind(Request $request) :: 사용자 아이디 찾기
 * public @method passwordFind(Request $request) :: 사용자 비밀번호 찾기
 * public @method refreshtoken(Request $request) :: 토큰 재발급
 */
class UserController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    // login(Request $request) :: 사용자 로그인
    public function login(Request $request)
    {
        # url에서 아이디, 비밀번호 추출
        $id = intval($request->get('id'));
        $password = $request->get('password');

        # 사용자 확인
        $user = User::where('student_id', $id)->first();
        if ($user === null) {
            return abort(403, __('aborts.does_not_match.user_id'));
        }

        # 비밀번호 확인
        if(!User::passwordDecode($user, $password)) {
            return abort(403, __('aborts.does_not_match.password'));
        }

        # 사용자 접속 ip 추가
        UserLogin::create([
            'club_id' => $user->club_id,
            'user_id' => $user->id,
            'ip' => $request->server->get('REMOTE_ADDR'),
        ]);

        # 사용자 최근 접속시간 갱신
        $user->last_login_at = now();

        # 토큰 존재확인
        $jwtToken = JwtToken::where('user_id', $user->id)->first();
        if($jwtToken === null) {
            # 토큰 자체가 없는 사용자 토큰 발급
            $token = JwtToken::jwtToken($user->id);

            $jwtToken = new JwtToken();
            $jwtToken->club_id = $user->club_id;
            $jwtToken->user_id = $token['user_id'];
            $jwtToken->access_token = $token['access_token'];
            $jwtToken->access_token_end_at = $token['access_token_end_at'];
            $jwtToken->refresh_token = $token['refresh_token'];
            $jwtToken->refresh_token_end_at = $token['refresh_token_end_at'];
        } else {
            # 토큰이 존재하는 사용자

            # 액세스 토큰이 만료된 사용자
            if(JwtToken::jwtAccessCheckToken($user->id) === null) {
                $token = JwtToken::jwtRefreshToken($user->id);

                # 재사용 토큰도 만료
                if($token === null) {
                    $token = JwtToken::jwtToken($user->id);
                    $jwtToken->access_token = $token['access_token'];
                    $jwtToken->access_token_end_at = $token['access_token_end_at'];
                    $jwtToken->refresh_token = $token['refresh_token'];
                    $jwtToken->refresh_token_end_at = $token['refresh_token_end_at'];
                } else {
                    # 액세스 토큰 재발급
                    $jwtToken->access_token = $token['access_token'];
                    $jwtToken->access_token_end_at = $token['access_token_end_at'];
                }
            }
        }
        JwtToken::updateOrCreate([
            'club_id' => $user->club_id,
            'user_id' => $user->id,
        ], [
            'access_token' => $jwtToken->access_token,
            'access_token_end_at' => $jwtToken->access_token_end_at,
            'refresh_token' => $jwtToken->refresh_token,
            'refresh_token_end_at' => $jwtToken->refresh_token_end_at,
        ]);

        # 데이터 정제
        $data = [
            'club_id' => $user->club_id,
            'user_id' => $user->id,
            'access_token' => $jwtToken->access_token,
            'access_token_end_at' => $jwtToken->access_token_end_at,
        ];

        return $data;
    }

    // joinMembership(Request $request) :: 사용자 회원가입
    public function joinMembership(Request $request)
    {
        $this->validate($request, [
            'club_code' => 'required|integer|min:1|max:9999',
            'department_code' => 'required|integer|min:1|max:9999',
            'student_id' => 'required|integer|regex:/^\d{7}$/',
            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:50',
            'phone' => 'nullable|array',
            'email' => 'required|string|regex:/^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i',
            'address' => 'nullable|string|max:255',
            'birthday' => 'required|string|regex:/^(\d{4})-?(\d{2})-?(\d{2})$/',
        ], [
            'clud_code.*' => __('validations.club_code'),
            'department_code.*' => __('validations.department_code'),
            'student_id.*' => __('validations.student_id'),
            'name.*' => __('validations.name'),
            'gender.*' => __('validations.gender'),
            'phone.*' => __('validations.phone'),
            'email.*' => __('validations.email'),
            'address.*' => __('validations.address'),
            'birthday.*' => __('validations.birthday'),
            '*' => __('validations.format')
        ]);

        $club_code = intval($request->input('club_code'));
        $department_code = intval($request->input('department_code'));
        $student_id = intval($request->input('student_id'));
        $name = $request->input('name');
        $gender = $request->input('gender');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $address = $request->input('address');
        $birthday = $request->input('birthday');

        # 클럽 조회
        $club = Club::where('code', $club_code)->first();
        # 학과 조회
        $department = Department::where('code', $department_code)->first();



        return [
            'club_code' => $club_code,
            'department_code' => $department_code,
            'student_id' => $student_id,
            'name' => $name,
            'gender' => $gender,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'birthday' => $birthday
        ];
    }

    // departmentCode(Request $request) :: 학과 조회
    public function departmentCode(Request $request)
    {

        $clubCode = $request->get('club_code');

        $club = Club::where('code', $clubCode)->first();
        if($club === null) {
            abort(403, __('aborts.does_not_exist.club_code'));
        }

        $departments = Department::where('club_id', $club->id)->select(['name', 'code'])->get();
        if($departments->isEmpty()) {
            abort(403, __('aborts.does_not_exist.department'));
        }

        return $departments;
    }


    // idFind(Request $request) :: 사용자 아이디 찾기
    public function idFind(Request $request)
    {
        $type = $request->input('type');
        if ($type === null) {
            abort(403, __('aborts.request'));
        }
    }

    // refreshtoken(Request $request) :: 토큰 재발급
    public function refreshtoken(Request $request)
    {
    }

    // passwordFind(Request $request) :: 사용자 비밀번호 찾기
    public function passwordFind(Request $request)
    {
    }
}
