<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $flight = User::create([
            'name' => ucwords('maro sanguineti'),
            'unitid' => 1,
            'eventid' => null,
            'unitname' => '',
            'email' => 'mario@revastaff.com',
            'password' => Hash::make('admin'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

        $flight = User::create([
            'name' => ucwords('Steven Smith'),
            'unitid' => 1,
            'eventid' => null,
            'unitname' => '',
            'email' => 'stsmith10282022@gmail.com',
            'password' => Hash::make('password'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

        $flight = User::create([
            'name' => ucwords('Dan'),
            'unitid' => 1,
            'eventid' => null,
            'unitname' => '',
            'email' => 'Dan',
            'password' => Hash::make('Dan'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

        $flight = User::create([
            'name' => ucwords('C manning'),
            'unitid' => 2,
            'eventid' => null,
            'unitname' => '',
            'email' => 'cmanning@event-essentials.net',
            'password' => Hash::make('admin'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

        $flight = User::create([
            'name' => ucwords('k taylor'),
            'unitid' => 3,
            'eventid' => null,
            'unitname' => '',
            'email' => 'ktaylor@event-essentials.net',
            'password' => Hash::make('admin'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

        $flight = User::create([
            'unitid' => 4,
            'eventid' => null,
            'unitname' => '',
            'name' => ucwords('marlou pepito'),
            'email' => 'marlou.pepito@event-essentials.net',
            'password' => Hash::make('admin'),
            'usertype' => 'Administrator',
            'access1' => 'On',
            'access2' => 'On',
            'access3' => 'On',
            'access4' => 'On',
            'access5' => 'On',
            'access6' => 'On',
            'access7' => 'On',
            'access8' => 'On',
            'access9' => 'On',
            'access10' => 'On',
            'access11' => 'On',
            'access12' => 'On',
            'access13' => 'On',
            'access14' => 'On',
            'access15' => 'On',
            'year' => date('Y'),
        ]);

    }
}
