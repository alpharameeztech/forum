<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;
class AdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = new Faker;

        $user = new User;

        $user->name = 'universe';
        $user->type = 'admin';
        $user->email = 'universe@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('987654321'); // password
        $user->remember_token = Str::random(20);

        $user->save();
    }
}
