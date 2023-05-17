<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;
use Carbon\Carbon;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;
use App\Models\Rank;
use App\Models\Team;
use App\Models\Member;

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
