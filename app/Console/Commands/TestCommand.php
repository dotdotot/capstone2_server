<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Faker\Factory as Faker;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;
use App\Models\Rank;
use App\Models\Team;
use App\Models\Member;
use App\Models\UserLogin;
use App\Models\RankPermission;
use App\Models\JwtToken;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capstone:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dd(Rank::where('club_id', 1)
        ->where('name', '방장')
        ->value('id'));
        dd(User::where('club_id', 1)
        ->where('rank_id', Rank::where('club_id', 1)
                                                ->where('name', '방장')
                                                ->value('id'))
        ->first());
        $faker = Faker::create('ko_KR');
        // 모든 부서 조회
        $departments = Team::with([
            'members.user:id,name',
            'closureDescendants'
        ])
            ->where('club_id', 1)
            ->orderByRaw('path collate "C"')
            ->get()
            ->map(function ($team, $index) {
                $team->type = 'team';

                $childrenTeams = $team->closureDescendants->pluck('descendant');
                // 하위부서 포함 사용자 수
                $team->number_of_user = Member::whereIn('team_id', $childrenTeams->toArray())->count();
                // 해당 부서 사용자 수
                $team->number_of_this_user = $team->members->where('default', true)->count();
                // 하위부서의 id
                $team->children_teams = $childrenTeams->diff([$team->id])->flatten()->toArray();
                // 해당 부서의 사용자
                $team->users = ($team->members->pluck('user.name')->toArray());
                // 최상위 부서는 회사
                if ($team->parent_id === null) {
                    $team->type = 'team';
                    $team->position = 0;
                }
                if ($index === 0) {
                    $team->parent_id = null;
                }

                return $team->only([
                    'id', 'club_id', 'parent_id', 'name', 'position', 'path',
                    'type', 'number_of_user', 'number_of_this_user', 'users', 'children_teams'
                ]);
            });

        dd($departments->toArray());

        dd(User::where('club_id', 1)->inRandomOrder()->select('id')->first()->value('id'));
        $a = UserLogin::where('user_id', 33)->first();
        dd($a);
        $departments = Department::where('club_id', 12)->select(['name', 'code'])->get();

        if($departments->isEmpty()) {
            dd('zz');
        }
        dd($departments);
        // dd($user->created_at);

        // Carbon::parse($condition['repeat_end_date']->toDateTime())
        //                                             ->setTimezone(env('APP_TIMEZONE', 'Asia/Seoul'))

        // $token = JwtToken::jwtToken($user);
        // JwtToken::create([
        //     'club_id' => $user->club_id,
        //     'user_id' => $user->id,
        //     'access_token' => $token['access_token'],
        //     'access_token_end_at' => $token['access_token_end_at'],
        //     'refresh_token' => $token['refresh_token'],
        //     'refresh_token_end_at' => $token['refresh_token_end_at']
        // ]);
        // dd($token);

        $user = User::where('name', '김준석')->first();
        $accessToken = JwtToken::where('user_id', $user->id)->value('access_token_end_at');
        $key = config('jwt.secret');

        if ($accessToken->isPast()) {
            echo "The target time has already passed.";
        } else {
            echo "The target time has not yet passed.";
        }

        dd(1);

        $a = null;
        try {
            $a = JWTAuth::setToken($accessToken)->authenticate();
        } catch (\Exception $e) {
            dd(1);
        }
        dd($a);


        $club = Club::where('name', 'C403')->first();
        $ranks = Rank::where('club_id', $club->id)->get();

        # 방장 권한
        $adminUserPermission = new RankPermission();
        $adminUserPermission->club_Id = $club->id;

        dd($ranks->where('club_id', $club->id)->where('name', '방장')->value('id'));
        $user = User::InRandomOrder()->select(['id'])->first()['id'];
        dd($user);
        $club = Club::where('name', 'C403')->select('id', 'name')->first();
        $department = Department::where('club_id', $club->id)->first();

        $userLogin = new UserLogin();
        $userLogin->club_id = $club->id;
        $userLogin->user_id = 10;
        $userLogin->ip = $faker->ipv4;
        $userLogin->save();
        dd(1);


        $faker = Faker::create('ko_KR');
        $team = Team::where('id', 2)->first();
        dd($team->closureAncestors->toArray());
        dd(Department::whereNotIn('name', ['컴퓨터공학과'])->inRandomOrder()->first());

        dd($faker->numerify('테스트학과 ##'));
        // faker->metropolitanCity
        // $faker->cellPhoneNumber;

        dd(Team::whereNotNull('parent_id')->inRandomOrder()->first()->id);

        dd(Team::where('name', '컴온')->first()->name);
        $club = Club::where('name', 'C403')->first();
        $department = Department::where('name', '컴퓨터공학과')->first();

        User::where('club_id', $club->id)
                ->where('department_id', $department->id)
                ->whereNotIn('rank_id', [1,2])
                ->get()
                ->each(function ($user) use ($club, $department) {
                    $member = new Member();
                    $member->club_id = $club->id;
                    $member->department_id = $department->id;
                    $member->user_id = $user->id;
                    $member->rank_id = $user->rank_id;
                    dd($user->whereIn('name', ['이승주', '윤성직', '유성훈', '장우철', '이민형', '김수진', '노혜민', '황수진', '김준석', '서정찬', '홍민선'])->get()->isNotEmpty());
                    if(collect($member)->whereIn('name', ['이승주', '윤성직', '유성훈', '장우철', '이민형', '김수진', '노혜민', '황수진', '김준석', '서정찬', '홍민선'])) {
                        $member->default = false;
                    }
                    $member->leader = false;
                });

        $club = Club::where('name', 'C403')->first();
        $lookLowTeam = new Team();
        $lookLowTeam->club_id = $club->id;
        $lookLowTeam->parent_id = 2;
        $lookLowTeam->name = '지란지교 패밀리';
        $lookLowTeam->position = Team::where('club_id', 1)->count();
        $lookLowTeam->path = 'C403 -> 룩 -> 지란지교 패밀리';
        $lookLowTeam->save();
        dd($club->id);

        $lookLowLowTeam = new Team([
            'club_id' => $club->id,
            'parent_id' => $lookLowTeam->id,
            'name' => '지란지교 소프트',
            'position' => Team::where('club_id', 1)->count(),
            'path' => $lookLowTeam->path . ' -> 지란지교 소프트' ,
        ]);

        $lookLowLowLowTeam  = new Team([
            'club_id' => $club->id,
            'parent_id' => $lookLowLowTeam->id,
            'name' => '컨버젼스개발팀',
            'position' => Team::where('club_id', 1)->count(),
            'path' => $lookLowLowTeam->path . ' -> 컨버젼스개발팀',
        ]);

        $lookLowTeam->save();
        $lookLowLowTeam->save();
        $lookLowLowLowTeam->save();
        dd(1);


        dd(collect(Rank::where('club_id', 1)->where('name', '방장')->select('id')->first())->first());

        dd($date = DateTime::createFromFormat('Ymd', '19980308'));
        $department = Club::where('id', 3)->first();
        dd($department);

        dd(collect(Club::where('name', 'C403')->select('name')->pluck('name')->toArray())->first());

        $min = 1000;  // Minimum value of the random code
        $max = 9999;  // Maximum value of the random code
        do {
            $randomCode = random_int($min, $max);
        } while (Club::where('code', 1)->get()->isNotEmpty());
        dd($randomCode);
    }
}
