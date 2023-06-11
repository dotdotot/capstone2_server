<?php

namespace App\Http\Controllers\Open;

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
class ClubController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    public function emergencyContactNetwork(Request $request)
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

        # 비상연락망 추출
        $emergencyContactNetwork = ClubEmergencyContactNetwork::where('club_id', $club->id)
        ->select(['email', 'phone', 'location'])
        ->first()
        ->toArray();
        if($emergencyContactNetwork === null) {
            return abort(403, __('aborts.club_doex_not_exist.emergency_contact_network'));
        }

        return $emergencyContactNetwork;
    }
}
