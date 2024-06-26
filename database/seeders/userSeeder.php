<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name"=>"admin",
            "email"=>"admin@gmail.com",
            "role"=>"admin",
            "img"=>"",
            "password"=>Hash::make('asdASD123@'),
        ]);

        User::create([
            "name"=>"user1",
            "email"=>"user1@gmail.com",
            "role"=>"user",
            "img"=>"",
            "password"=>Hash::make('asdASD123'),
        ]);
        
        User::create([
            "name"=>"user2",
            "email"=>"user2@gmail.com",
            "role"=>"user",
            "img"=>"",
            "password"=>Hash::make('asdASD123'),
        ]);

        User::create([
            "name"=>"user3",
            "email"=>"user3@gmail.com",
            "role"=>"user",
            "img"=>"",
            "password"=>Hash::make('asdASD123'),
        ]);
    }
}