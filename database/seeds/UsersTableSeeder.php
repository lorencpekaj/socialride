<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // remove all existing seeded users
        App\User::where('email', 'like', '%@example.%')
            ->get()
            ->each(function ($user) {
                $user->delete();
            });

        // generate random users
        factory(App\User::class, 5)
            ->create([
                'freeze_location' => 1,
            ])
            ->each(function ($user) {
                // fake locations
                $user
                    ->locations()
                    ->save(
                        factory(App\UserLocation::class)->make()
                    );

                // fake trips
                $trip = $user
                    ->trip()
                    ->save(
                        factory(App\Trip::class)->make()
                    );

                if ($trip) {
                    // create pick up and drop off for the trip
                    $trip
                        ->pickUp()
                        ->create([
                            'lat' => $user->locations()->first()->lat,
                            'lng' => $user->locations()->first()->lng,
                            'address' => 'Randomly Generated Pick Up'
                        ]);

                    // create a random drop off location for the trip
                    $trip
                        ->dropOff()
                        ->save(
                            factory(App\DropOff::class)->make()
                        );
                }
            });
    }
}
