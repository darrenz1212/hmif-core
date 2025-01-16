<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HmifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'staff User',
            'email' => 'staff@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Ganti dengan password yang diinginkan
            'role' => 'stafflab', // Tambahkan kolom 'role' di tabel jika belum ada
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
