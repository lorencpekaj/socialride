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

        // todo: filter by logged in users
        $users = User::where('id', '<>', $user->id)
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

        // if ($user->driving === false) {
        //     return $this->error('User is not driving');
        // }

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
     * Display the specified resource.
     *
     * @param  \App\UserLocation  $userLocation
     * @return \Illuminate\Http\Response
     */
    public function show(UserLocation $userLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserLocation  $userLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLocation $userLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserLocation  $userLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLocation $userLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserLocation  $userLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLocation $userLocation)
    {
        //
    }
}
