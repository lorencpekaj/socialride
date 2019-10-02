<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickUp extends Model
{
    protected $table = 'pick_up';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trip_id', 'lat', 'lng', 'address',
    ];

    /**
     * Get the pick up location's trip data
     */
    public function trip()
    {
        return $this->belongsTo('App\Trip');
    }
}
