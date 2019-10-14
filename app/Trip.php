<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'trips';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id'
    ];

    /**
     * Return the drop off location of the trip
     */
    public function dropOff()
    {
        return $this->hasOne('App\DropOff');
    }

    /**
     * Return the pick up location of the trip
     */
    public function pickUp()
    {
        return $this->hasOne('App\PickUp');
    }

    /**
     * Return the passenger of the trip
     */
    public function passenger()
    {
        return $this->hasOne('App\User', 'id', 'passenger_id');
    }

    /**
     * Return the passenger of the trip
     */
    public function driver()
    {
        return $this->hasOne('App\User', 'id', 'driver_id');
    }
}
