<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropOff extends Model
{
    protected $table = 'drop_off';

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
     * Get the drop off location's trip data
     */
    public function trip()
    {
        return $this->belongsTo('App\Trip');
    }
}
