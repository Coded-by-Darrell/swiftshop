<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $vendors = [
            ['user_id' => 1, 'business_name' => 'Audio Store', 'business_email' => 'audio@store.com', 'business_phone' => '09123456789', 'business_address' => 'Manila, Philippines', 'status' => 'approved'],
            ['user_id' => 2, 'business_name' => 'Tech Hub', 'business_email' => 'tech@hub.com', 'business_phone' => '09123456790', 'business_address' => 'Quezon City, Philippines', 'status' => 'approved'],
            ['user_id' => 3, 'business_name' => 'Gaming World', 'business_email' => 'gaming@world.com', 'business_phone' => '09123456791', 'business_address' => 'Makati, Philippines', 'status' => 'approved'],
            ['user_id' => 4, 'business_name' => 'Cable Store', 'business_email' => 'cable@store.com', 'business_phone' => '09123456792', 'business_address' => 'Pasig, Philippines', 'status' => 'approved'],
            ['user_id' => 5, 'business_name' => 'Apple Store', 'business_email' => 'apple@store.com', 'business_phone' => '09123456793', 'business_address' => 'BGC, Philippines', 'status' => 'approved'],
            ['user_id' => 6, 'business_name' => 'Camera Pro', 'business_email' => 'camera@pro.com', 'business_phone' => '09123456794', 'business_address' => 'Ortigas, Philippines', 'status' => 'approved'],
            ['user_id' => 7, 'business_name' => 'Power Tech', 'business_email' => 'power@tech.com', 'business_phone' => '09123456795', 'business_address' => 'Alabang, Philippines', 'status' => 'approved'],
            ['user_id' => 8, 'business_name' => 'Samsung Store', 'business_email' => 'samsung@store.com', 'business_phone' => '09123456796', 'business_address' => 'Cebu, Philippines', 'status' => 'approved'],
            ['user_id' => 9, 'business_name' => 'Chrome Hearts Official Store', 'business_email' => 'chrome@hearts.com', 'business_phone' => '09123456797', 'business_address' => 'Davao, Philippines', 'status' => 'approved'],
            ['user_id' => 10, 'business_name' => 'Made in Manila', 'business_email' => 'made@manila.com', 'business_phone' => '09123456798', 'business_address' => 'Manila, Philippines', 'status' => 'approved'],
            ['user_id' => 11, 'business_name' => 'DBTK Official Store', 'business_email' => 'dbtk@store.com', 'business_phone' => '09123456799', 'business_address' => 'Iloilo, Philippines', 'status' => 'approved'],
            ['user_id' => 12, 'business_name' => 'Green Home Co', 'business_email' => 'green@home.com', 'business_phone' => '09123456800', 'business_address' => 'Baguio, Philippines', 'status' => 'approved'],
            ['user_id' => 13, 'business_name' => 'Bright Living', 'business_email' => 'bright@living.com', 'business_phone' => '09123456801', 'business_address' => 'Batangas, Philippines', 'status' => 'approved'],
            ['user_id' => 14, 'business_name' => 'Kitchen Pro', 'business_email' => 'kitchen@pro.com', 'business_phone' => '09123456802', 'business_address' => 'Laguna, Philippines', 'status' => 'approved'],
            ['user_id' => 15, 'business_name' => 'Garden Solutions', 'business_email' => 'garden@solutions.com', 'business_phone' => '09123456803', 'business_address' => 'Cavite, Philippines', 'status' => 'approved'],
            ['user_id' => 16, 'business_name' => 'Gaming Central', 'business_email' => 'gaming@central.com', 'business_phone' => '09123456804', 'business_address' => 'Rizal, Philippines', 'status' => 'approved'],
            ['user_id' => 17, 'business_name' => 'Pro Gamers Hub', 'business_email' => 'pro@gamers.com', 'business_phone' => '09123456805', 'business_address' => 'Bulacan, Philippines', 'status' => 'approved'],
            ['user_id' => 18, 'business_name' => 'Comfort Gaming', 'business_email' => 'comfort@gaming.com', 'business_phone' => '09123456806', 'business_address' => 'Pampanga, Philippines', 'status' => 'approved'],
            ['user_id' => 19, 'business_name' => 'Mouse Masters', 'business_email' => 'mouse@masters.com', 'business_phone' => '09123456807', 'business_address' => 'Tarlac, Philippines', 'status' => 'approved'],
            ['user_id' => 20, 'business_name' => 'Photo Pro Store', 'business_email' => 'photo@pro.com', 'business_phone' => '09123456808', 'business_address' => 'Nueva Ecija, Philippines', 'status' => 'approved'],
            ['user_id' => 21, 'business_name' => 'Studio Lights Co', 'business_email' => 'studio@lights.com', 'business_phone' => '09123456809', 'business_address' => 'Zambales, Philippines', 'status' => 'approved'],
            ['user_id' => 22, 'business_name' => 'Lens Care Plus', 'business_email' => 'lens@care.com', 'business_phone' => '09123456810', 'business_address' => 'Bataan, Philippines', 'status' => 'approved'],
            ['user_id' => 23, 'business_name' => 'Remote Controls Inc', 'business_email' => 'remote@controls.com', 'business_phone' => '09123456811', 'business_address' => 'Aurora, Philippines', 'status' => 'approved'],
            ['user_id' => 24, 'business_name' => 'Audio Masters', 'business_email' => 'audio@masters.com', 'business_phone' => '09123456812', 'business_address' => 'Palawan, Philippines', 'status' => 'approved'],
            ['user_id' => 25, 'business_name' => 'Sound Solutions', 'business_email' => 'sound@solutions.com', 'business_phone' => '09123456813', 'business_address' => 'Pangasinan, Philippines', 'status' => 'approved'],
            ['user_id' => 26, 'business_name' => 'Recording Studio Pro', 'business_email' => 'recording@studio.com', 'business_phone' => '09123456814', 'business_address' => 'La Union, Philippines', 'status' => 'approved'],
            ['user_id' => 27, 'business_name' => 'Premium Audio', 'business_email' => 'premium@audio.com', 'business_phone' => '09123456815', 'business_address' => 'Benguet, Philippines', 'status' => 'approved'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}