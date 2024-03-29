<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Http\Controllers\Controller;
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
use App\Models\Menu;
use App\Models\ClubEmergencyContactNetwork;

/**
 * public @method menu(Request $request) ::
 */
class CommonBoardController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

}
