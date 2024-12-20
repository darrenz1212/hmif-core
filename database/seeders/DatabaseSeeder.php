<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'HMIF User',
            'email' => 'hmif@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
            'role' => 'hmif', // Tambahkan kolom 'role' di tabel jika belum ada
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
