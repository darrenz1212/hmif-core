<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'HMIF User',
                'email' => 'hmif@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
                'role' => 'hmif', // Pastikan kolom 'role' ada di tabel
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kalab User',
                'email' => 'kalab@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
                'role' => 'kalab', // Role untuk Kalab
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Lab User',
                'email' => 'stafflab@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
                'role' => 'stafflab', // Role untuk Staff Lab
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
