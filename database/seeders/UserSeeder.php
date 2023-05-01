<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
         'name'        => 'admin',
         'email'       => 'admin@admin.com',
         'password'    => password_hash('123', PASSWORD_DEFAULT),
         'hour_entry'  => '07:00',
         'hour_pause'  => '12:00',
         'hour_return' => '13:00',
         'hour_exit'   => '17:00',
         'isAdmin'     => '1'
       ]);
    }
}
