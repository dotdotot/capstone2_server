<?php

namespace App\Http\Controllers\Open;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
use App\Models\CCTVConsent;
use App\Models\Club;
use App\Models\Department;
use App\Models\JwtToken;
use App\Models\Member;
use App\Models\ProjectConsent;
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
class AccountController extends Controller
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
        if(strcmp($user->password, $password) !== 0) {
            return abort(403, __('aborts.does_not_match.password'));
        }

        # 사용자 권한 확인
        $rank_name = Rank::where('club_id', $user->club_id)->where('id', $user->rank_id)->value('name');

        # 사용자 접속 기기 확인
        $device = $request->headers->get('User-Agent');
        if(strpos($device, "Windows") !== false) {
            $device = "Windows";
        } elseif(strpos($device, "Mac OS") !== false) {
            $device = "Mac OS";
        } elseif(strpos($device, "Android") !== false) {
            $device = "Android";
        } elseif(strpos($device, "Linux") !== false) {
            $device = "Linux";
        } else {
            $device = "Test";
        }
        # 사용자 접속 ip 추가
        UserLogin::create([
            'club_id' => $user->club_id,
            'user_id' => $user->id,
            'ip' => $request->server->get('REMOTE_ADDR'),
            'device' => $device,
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
            'rank' => $rank_name,
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
            'cctv_consent' => 'required|boolean',
            'project_consent' => 'required|boolean',
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
            'cctv_consent.*' => __('validations.cctv_consent'),
            'project_consent.*' => __('validations.project_consent'),
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
        $cctv_consent = $request->input('cctv_consent');
        $project_consent = $request->input('project_consent');

        # 클럽 조회
        $club = Club::where('code', $club_code)->first();
        if($club === null) {
            return abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 학과 조회
        $department = Department::where('code', $department_code)->first();
        if($department === null) {
            return abort(403, __('aborts.does_not_exist.department'));
        }

        # 이미 존재하는 학번인지 검사
        if(User::where('student_id', $student_id)->first() !== null) {
            abort(403, __('aborts.does_not_exist.student_id'));
        }
        # 사용자 생성
        $user = User::create([
            'club_id' => $club->id,
            'department_id' => $department->id,
            'rank_id' => 3,
            'student_id' => $student_id,
            'name' => $name,
            'gender' => $gender,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'birth_date' => $birthday,
            'password' => $email
        ]);

        # CCTV 동의
        CCTVConsent::create([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'consent' => $cctv_consent
        ]);
        # 프로젝트 동의
        ProjectConsent::create([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'consent' => $project_consent
        ]);

        return response()->json([
            'result' => 'success'
        ], 201);
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
        $club_code = intval($request->input('club_code'));
        $name = $request->input('name');
        $email = $request->input('email');
        if ($club_code === null || $name === null || $email === null) {
            abort(403, __('aborts.request'));
        }

        # 클럽 조회
        $club = Club::where('code', $club_code)->first();
        if($club === null) {
            abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 조회
        $user = User::where('name', $name)
                            ->where('email', $email)
                            ->first();
        if($user === null) {
            abort(403, __('aborts.does_not_exist.user'));
        }

        return [
            'user_id' => $user->id,
            'student_id' => $user->student_id
        ];
    }

    // passwordFind(Request $request) :: 사용자 비밀번호 찾기
    public function passwordFind(Request $request)
    {
        dd($request);
        $club_code = intval($request->input('club_code'));
        $name = $request->input('name');
        $student_id = intval($request->input('student_id'));
        if ($club_code === null || $name === null || $student_id === null) {
            abort(403, __('aborts.request'));
        }

        # 클럽 조회
        $club = Club::where('code', $club_code)->first();
        if($club === null) {
            abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 조회
        $user = User::where('name', $name)
                            ->where('student_id', $student_id)
                            ->first();
        if($user === null) {
            abort(403, __('aborts.does_not_exist.user'));
        }

        return [
            'user_id' => $user->id,
            'student_id' => $user->student_id,
            'password' => $user->password
        ];
    }

    // refreshtoken(Request $request) :: 토큰 재발급
    public function token(Request $request)
    {
        $club_id = intval($request->input('club_id'));
        $user_id = intval($request->input('user_id'));

        # 클럽 조회
        $club = Club::where('id', $club_id)->first();
        if($club === null) {
            abort(403, __('aborts.does_not_exist.club_code'));
        }

        # 사용자 조회
        $user = User::where('id', $user_id)->first();
        if($user === null) {
            abort(403, __('aborts.does_not_exist.user'));
        }

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

        return [
            "club_id" => $club->id,
            "user_id" => $user->id,
            "access_token" => $jwtToken->access_token,
            "access_token_end_at" => $jwtToken->access_token_end_at,
        ];
    }
}
