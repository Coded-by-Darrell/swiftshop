<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrowseController extends Controller
{
   public function index(){
        $userName = 'Darrell C. Ocampo';

        $specialDeals = [
            [
                'name' => 'Wireless Headphones',
                'price' => '79.99',
                'old_price' => '99.99',
                'badge' => '20% off', 
                'rating' => 4.5,
                'store' => 'Audio Store'
            ],
            [
                'name' => 'Smart Watch',
                'price' => '199.99',
                'old_price' => '249.99',
                'badge' => '20% off',
                'rating' => 4.7,
                'store' => 'Tech Hub'
            ],
            [
                'name' => 'Gaming Mouse',
                'price' => '39.99',
                'old_price' => '59.99',
                'badge' => '33% OFF',
                'rating' => 4.6,
                'store' => 'Gaming World'
            ],
            [
                'name' => 'USB-C Cable',
                'price' => '12.99',
                'old_price' => '19.99',
                'badge' => '35% OFF',
                'rating' => 4.5,
                'store' => 'Cable Store'
            ]
        ];
        
        // New Releases products
        $newReleases = [
            [
                'name' => 'iPhone 15 Case',
                'price' => '29.99',
                'badge' => 'New',
                'rating' => 4.8,
                'store' => 'Apple Store'
            ],
            [
                'name' => 'Mechanical Keyboard',
                'price' => '129.99',
                'badge' => 'New',
                'rating' => 4.9,
                'store' => 'Tech Hub'
            ],
            [
                'name' => '4K Webcam',
                'price' => '89.99',
                'badge' => 'New',
                'rating' => 4.5,
                'store' => 'Camera Pro'
            ],
            [
                'name' => 'Wireless Charger',
                'price' => '34.99',
                'badge' => 'New',
                'rating' => 4.4,
                'store' => 'Power Tech'
            ],
         
        ];
        
        // Electronics products
        $electronics = [
            [
                'name' => 'MacBook Pro',
                'price' => '1999.99',
                'rating' => 4.9,
                'store' => 'Apple Store'
            ],
            [
                'name' => 'iPad Air',
                'price' => '599.99',
                'rating' => 4.8,
                'store' => 'Apple Store'
            ],
            [
                'name' => 'AirPods Pro',
                'price' => '249.99',
                'rating' => 4.7,
                'store' => 'Apple Store'
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'price' => '899.99',
                'rating' => 4.6,
                'store' => 'Samsung Store'
            ],
            
        ];
        
        return view('browse.index', compact('userName', 'specialDeals', 'newReleases', 'electronics'));
    }
}

