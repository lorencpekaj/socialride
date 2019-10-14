<?php

namespace App\Http\Controllers;

use App\User;
use App\UserLocation;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all DRIVERS specifically
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        $users = User::where('id', '<>', $user->id)
            ->recentlyLogged()
            ->with('locations')
            ->get();

        // transform the user data into just unique positions
        $userLocations = $users->map(function ($user) {
            return [
                'position' => [
                    'lat' => $user->locations[0]->lat ?? '',
                    'lng' => $user->locations[0]->lng ?? ''
                ]
            ];
        });
        return response()->json($userLocations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();

        if ($user->freeze_location) {
            return $this->error('User location frozen, cannot update!');
        }

        $userLocation = new UserLocation();
        $userLocation->user_id = $user->id;
        $userLocation->lat = $request->json()->get('lat');
        $userLocation->lng = $request->json()->get('lng');
        $userLocation->save();

        return $this->success(
            $userLocation->only(['user_id', 'lat', 'lng'])
        );
    }

    /**
     * (Un-)Freeze a user location
     *
     * @return \Illuminate\Http\Response
     */
    public function freeze()
    {
        $user = \Auth::user();
        $user->freeze_location = !$user->freeze_location;
        $user->save();
        return $this->success(
            $user->only(['id', 'freeze_location'])
        );
    }
}
