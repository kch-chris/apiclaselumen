<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Factory(App\User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234567890')
        ]);
    }
}
