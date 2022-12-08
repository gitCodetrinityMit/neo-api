<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['id'    =>  1,
            'user_name' =>   'Nivya Vaghasiya',
            'email' =>   'nivya.codetrinity@gmail.com',
            'first_name'    =>   'nivya',
            'last_name' =>   'vaghasiya',
            'email_verified_at' =>   NULL,
            'password'  =>   Hash::make('123456789'),
            'user_type' =>   0,
            'remember_token'    =>    'e2b3a89e-b2f6-4e18-9323-d6b8c69a7892',
            ],
            [
                'id'    =>  2,
                'user_name' =>   'Jorge Vaghasiya',
                'email' =>   'jorge.codetrinity@gmail.com',
                'first_name'    =>   'nivya',
                'last_name' =>   'vaghasiya',
                'email_verified_at' =>   NULL,
                'password'  =>   Hash::make('123456789'),
                'user_type' =>   1,
                'remember_token'    =>    'e2b3a89e-b2f6-4e18-9323-d6b8c69a7892',
            ]
        ];
        foreach($users as $user){
            User::create($user);
        }
    }
}
