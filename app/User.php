<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return an array of the most recent locations of a user
     *
     * @return App\UserLocation User Locations
     */
    public function locations()
    {
        return $this
            ->hasMany('App\UserLocation')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Checks if a user's last recorded location was less than 60 minutes ago
     */
    public function scopeRecentlyLogged($query)
    {
        return $query->whereHas('locations', function ($location) {
            $tenMinsAgo = Carbon::now()->subMinutes(60)->toDateTimeString();
            $location->where('created_at', '>', $tenMinsAgo);
        });
    }
}
