<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // Şifreyi hash'liyoruz
            'role' => 'admin', // Rolü admin olarak belirtiyoruz
        ]);

        // 200 adet normal kullanıcı oluştur
        // User::factory(200)->create([
        //     'role' => 'user', // Rolü user olarak belirtiyoruz
        // ]);
    }
}
