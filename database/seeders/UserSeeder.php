<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $userAdmin = User::create([
            'nik' => '3305101703900001',
            'name' => 'Dimas Kenzo',
            'email' => 'dimas@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('075410025'),
            'remember_token' => Str::random(10),
            'position' => 'Backend Enginners',
            'no_hp' => '085743154138',
            'role_id' => $roleAdmin->id,
        ]);        
        $userAdmin->assignRole($roleAdmin);

        $roleGuest = Role::create(['name' => 'guest']);
        $userGuest = User::create([
            'nik' => '3305101703900002',
            'name' => 'Kenan Quinno',
            'email' => 'kenan@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('075410026'),
            'remember_token' => Str::random(10),
            'position' => 'Backend Developer',
            'no_hp' => '085743154138',
            'role_id' => $roleGuest->id,
        ]);

        $userGuest->assignRole($roleGuest);
    }
}
