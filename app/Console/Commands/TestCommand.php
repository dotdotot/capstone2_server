<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime; // Add this line
use Carbon\Carbon;

use App\Models\Club;
use App\Models\Department;
use App\Models\User;

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
