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

        $existingTrip = Trip::where('passenger_id', $user->id)
            ->orWhere('driver_id', $user->id)
            ->exists();
        if ($existingTrip) {
            return $this->error('This user is already in a trip');
        }

        $trip = new Trip;
        $trip->passenger_id = $user->id;
        $trip->distance = $request->input('distance');
        $trip->duration = $request->input('duration');
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
     * Request a pickup by a passenger
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acceptPickup(Trip $trip, Request $request)
    {
        if ($trip->driver_id !== null) {
            return $this->error('Somebody has already decided to drive this');
        }

        $success = $trip->update([
            'driver_id' => $request->input('driver_id')
        ]);

        if ($success) {
            return $this->success($trip->toArray());
        } else {
            return $this->error('Could not allocate driver');
        }
    }

    /**
     * Display a listing of all unallocated trips for drivers
     *
     * @return \Illuminate\Http\Response
     */
    public function availableTrips()
    {
        // check if the user is currently in a trip
        $currentUser = \Auth::user();
        $existingTrip = Trip::where('driver_id', $currentUser->id)
            ->orWhere('passenger_id', $currentUser->id)
            ->with(['pickUp', 'dropOff', 'driver', 'passenger'])
            ->first();

        if ($existingTrip) {
            return $this->success([
                'current_trip' => [
                    'id' => $existingTrip->id,
                    'passenger_id' => $existingTrip->passenger_id,
                    'passenger_name' => $existingTrip->passenger->name,
                    'driver_id' => $existingTrip->driver_id,
                    'driver_name' => $existingTrip->driver->name ?? '',
                    'driver_pos' => $existingTrip->driver ?
                        $existingTrip->driver->locations->first()->only(['lat', 'lng']) :
                        null,
                    'pick_up' => $existingTrip->pickUp->only(['lat', 'lng']),
                    'drop_off' => $existingTrip->dropOff->only(['lat', 'lng']),
                ]
            ]);
        }

        // otherwise just show all trips
        $trips = Trip::with(['pickUp', 'dropOff', 'passenger'])
            ->whereNull('driver_id')
            ->get()
            ->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'passenger' => [
                        'id' => $trip->passenger->id,
                        'name' => ucwords($trip->passenger->name),
                    ],
                    'duration' => $trip->duration,
                    'distance' => $trip->distance,
                    'pick_up' => $trip->pickUp->only(['lat', 'lng', 'address']),
                    'drop_off' => $trip->dropOff->only(['lat', 'lng', 'address']),
                ];
            })
            ->toArray();
        return $this->success($trips);
    }

    /**
     * Delete a trip
     *
     * @param  App\Trip $trip
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Trip $trip, Request $request)
    {
        $user = \Auth::user();
        if ($trip->driver_id !== $user->id && $trip->passenger_id !== $user->id) {
            return $this->error('You are not allocated in this trip');
        } else if ($trip->delete()) {
            return $this->success($trip->only('id'));
        } else {
            return $this->error('Unable to remove resource');
        }
    }
}
