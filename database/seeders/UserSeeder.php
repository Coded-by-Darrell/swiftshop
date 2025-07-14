<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Audio Store Owner', 'email' => 'audio@store.com', 'password' => Hash::make('password123')],
            ['name' => 'Tech Hub Owner', 'email' => 'tech@hub.com', 'password' => Hash::make('password123')],
            ['name' => 'Gaming World Owner', 'email' => 'gaming@world.com', 'password' => Hash::make('password123')],
            ['name' => 'Cable Store Owner', 'email' => 'cable@store.com', 'password' => Hash::make('password123')],
            ['name' => 'Apple Store Owner', 'email' => 'apple@store.com', 'password' => Hash::make('password123')],
            ['name' => 'Camera Pro Owner', 'email' => 'camera@pro.com', 'password' => Hash::make('password123')],
            ['name' => 'Power Tech Owner', 'email' => 'power@tech.com', 'password' => Hash::make('password123')],
            ['name' => 'Samsung Store Owner', 'email' => 'samsung@store.com', 'password' => Hash::make('password123')],
            ['name' => 'Chrome Hearts Owner', 'email' => 'chrome@hearts.com', 'password' => Hash::make('password123')],
            ['name' => 'Made in Manila Owner', 'email' => 'made@manila.com', 'password' => Hash::make('password123')],
            ['name' => 'DBTK Store Owner', 'email' => 'dbtk@store.com', 'password' => Hash::make('password123')],
            ['name' => 'Green Home Owner', 'email' => 'green@home.com', 'password' => Hash::make('password123')],
            ['name' => 'Bright Living Owner', 'email' => 'bright@living.com', 'password' => Hash::make('password123')],
            ['name' => 'Kitchen Pro Owner', 'email' => 'kitchen@pro.com', 'password' => Hash::make('password123')],
            ['name' => 'Garden Solutions Owner', 'email' => 'garden@solutions.com', 'password' => Hash::make('password123')],
            ['name' => 'Gaming Central Owner', 'email' => 'gaming@central.com', 'password' => Hash::make('password123')],
            ['name' => 'Pro Gamers Owner', 'email' => 'pro@gamers.com', 'password' => Hash::make('password123')],
            ['name' => 'Comfort Gaming Owner', 'email' => 'comfort@gaming.com', 'password' => Hash::make('password123')],
            ['name' => 'Mouse Masters Owner', 'email' => 'mouse@masters.com', 'password' => Hash::make('password123')],
            ['name' => 'Photo Pro Owner', 'email' => 'photo@pro.com', 'password' => Hash::make('password123')],
            ['name' => 'Studio Lights Owner', 'email' => 'studio@lights.com', 'password' => Hash::make('password123')],
            ['name' => 'Lens Care Owner', 'email' => 'lens@care.com', 'password' => Hash::make('password123')],
            ['name' => 'Remote Controls Owner', 'email' => 'remote@controls.com', 'password' => Hash::make('password123')],
            ['name' => 'Audio Masters Owner', 'email' => 'audio@masters.com', 'password' => Hash::make('password123')],
            ['name' => 'Sound Solutions Owner', 'email' => 'sound@solutions.com', 'password' => Hash::make('password123')],
            ['name' => 'Recording Studio Owner', 'email' => 'recording@studio.com', 'password' => Hash::make('password123')],
            ['name' => 'Premium Audio Owner', 'email' => 'premium@audio.com', 'password' => Hash::make('password123')],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}