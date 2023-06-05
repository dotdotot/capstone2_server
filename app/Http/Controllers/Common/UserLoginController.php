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

class UserLoginController extends Controller
{
    public function __construct(public Client $client)
    {
        $this->client = $client;
    }

    public function servers(Request $request)
    {
        $this->validate($request, [
            'account' => 'nullable|string',
        ], [
            '*' => __('validations.format')
        ]);

        if ($server === null) {
            abort(403, __('aborts.no_access'));
        }

        return response()->json($server->hosts);
    }
}
