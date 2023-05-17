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
        $topTeam = new Team([
            'club_id' => 1,
            'parent_id' => null,
            'name' => 'C403',
            'position' => Team::where('club_id', 1)->count(),
            'path' => 'C403',
        ]);

        $a = new Team([
            'club_id' => 1,
            'parent_id' => 4,
            'name' => '룩밑에팀에밑에팀',
            'position' => Team::where('club_id', 1)->count(),
            'path' => $topTeam->name . ' => ' . '룩 => ' . '룩밑에팀 => 룩밑에팀에밑에팀'
        ]);
        $a->save();

        dd($a);

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
