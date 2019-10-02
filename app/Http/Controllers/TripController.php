<?php

namespace App\Http\Controllers;

use App\Trip;
use App\PickUp;
use App\DropOff;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Request a pickup by a passenger
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestPickup(Request $request)
    {
        $user = \Auth::user();
        // if ($user->is_driving !== false) {
        //     return $this->error('This user is currently driving');
        // }

        $existingTrip = Trip::where('passenger_id', $user->id)
            ->orWhere('driver_id', $user->id)
            ->exists();
        if ($existingTrip) {
            return $this->error('This user is already in a trip');
        }

        $trip = new Trip;
        $trip->passenger_id = $user->id;
        $trip->driver_id = null;
        if ($trip->save()) {
            $trip->pickUp()->create([
                'trip_id' => $trip->id,
                'lat' => $request->input('pick_up.lat'),
                'lng' => $request->input('pick_up.lng'),
                'address' => $request->input('pick_up.address'),
            ]);
            $trip->dropOff()->create([
                'trip_id' => $trip->id,
                'lat' => $request->input('drop_off.lat'),
                'lng' => $request->input('drop_off.lng'),
                'address' => $request->input('drop_off.address'),
            ]);
            return $this->success($trip->only(['id']));
        } else {
            return $this->error('Unable to request trip');
        }
    }

    /**
     * Display a listing of all unallocated trips for drivers
     *
     * @return \Illuminate\Http\Response
     */
    public function availableTrips()
    {
        $trips = Trip::with(['pickUp', 'dropOff'])
            ->whereNull('driver_id')
            ->get()
            ->each(function ($trip) {
                $trip->pickUp->makeHidden(['id', 'trip_id']);
                $trip->dropOff->makeHidden(['id', 'trip_id']);
            })
            ->makeHidden([
                'driver_id',
                'updated_at'
            ])
            ->toArray();
        return $this->success($trips);
    }
}
