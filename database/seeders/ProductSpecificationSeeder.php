<?php

namespace Database\Seeders;

use App\Models\ProductSpecification;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $specifications = [
            // Electronics Category (ID: 1)
            1 => [ // Wireless Headphones
                ['spec_name' => 'Brand', 'spec_value' => 'Sony', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'WH-1000XM4', 'display_order' => 2],
                ['spec_name' => 'Type', 'spec_value' => 'Over-ear', 'display_order' => 3],
                ['spec_name' => 'Connectivity', 'spec_value' => 'Bluetooth 5.0, 3.5mm', 'display_order' => 4],
                ['spec_name' => 'Battery Life', 'spec_value' => '30 hours', 'display_order' => 5],
                ['spec_name' => 'Weight', 'spec_value' => '254g', 'display_order' => 6],
                ['spec_name' => 'Noise Cancellation', 'spec_value' => 'Active', 'display_order' => 7],
                ['spec_name' => 'Warranty', 'spec_value' => '1 Year', 'display_order' => 8]
            ],
            2 => [ // Smart Watch
                ['spec_name' => 'Brand', 'spec_value' => 'Apple', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'Watch Series 9', 'display_order' => 2],
                ['spec_name' => 'Display Size', 'spec_value' => '45mm', 'display_order' => 3],
                ['spec_name' => 'Display Type', 'spec_value' => 'OLED Retina', 'display_order' => 4],
                ['spec_name' => 'Battery Life', 'spec_value' => '18 hours', 'display_order' => 5],
                ['spec_name' => 'Water Resistance', 'spec_value' => '50 meters', 'display_order' => 6],
                ['spec_name' => 'Connectivity', 'spec_value' => 'WiFi, Bluetooth, Cellular', 'display_order' => 7],
                ['spec_name' => 'Warranty', 'spec_value' => '1 Year', 'display_order' => 8]
            ],
            3 => [ // Gaming Mouse
                ['spec_name' => 'Brand', 'spec_value' => 'Logitech', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'G502 Hero', 'display_order' => 2],
                ['spec_name' => 'DPI', 'spec_value' => '25,600 DPI', 'display_order' => 3],
                ['spec_name' => 'Buttons', 'spec_value' => '11 programmable', 'display_order' => 4],
                ['spec_name' => 'Connectivity', 'spec_value' => 'USB-A', 'display_order' => 5],
                ['spec_name' => 'Weight', 'spec_value' => '121g', 'display_order' => 6],
                ['spec_name' => 'RGB Lighting', 'spec_value' => 'Yes', 'display_order' => 7],
                ['spec_name' => 'Warranty', 'spec_value' => '2 Years', 'display_order' => 8]
            ],
            4 => [ // USB-C Cable
                ['spec_name' => 'Brand', 'spec_value' => 'Anker', 'display_order' => 1],
                ['spec_name' => 'Length', 'spec_value' => '6 feet / 1.8m', 'display_order' => 2],
                ['spec_name' => 'Charging Speed', 'spec_value' => '100W PD', 'display_order' => 3],
                ['spec_name' => 'Data Transfer', 'spec_value' => 'USB 3.1 Gen 2', 'display_order' => 4],
                ['spec_name' => 'Material', 'spec_value' => 'Braided Nylon', 'display_order' => 5],
                ['spec_name' => 'Compatibility', 'spec_value' => 'USB-C devices', 'display_order' => 6],
                ['spec_name' => 'Warranty', 'spec_value' => '18 Months', 'display_order' => 7]
            ],
            5 => [ // iPhone 15 Case
                ['spec_name' => 'Brand', 'spec_value' => 'OtterBox', 'display_order' => 1],
                ['spec_name' => 'Compatibility', 'spec_value' => 'iPhone 15', 'display_order' => 2],
                ['spec_name' => 'Material', 'spec_value' => 'Polycarbonate + TPU', 'display_order' => 3],
                ['spec_name' => 'Drop Protection', 'spec_value' => '4X military standard', 'display_order' => 4],
                ['spec_name' => 'Wireless Charging', 'spec_value' => 'Compatible', 'display_order' => 5],
                ['spec_name' => 'Color', 'spec_value' => 'Clear', 'display_order' => 6],
                ['spec_name' => 'Warranty', 'spec_value' => '1 Year', 'display_order' => 7]
            ],
            9 => [ // MacBook Pro
                ['spec_name' => 'Brand', 'spec_value' => 'Apple', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'MacBook Pro 14"', 'display_order' => 2],
                ['spec_name' => 'Processor', 'spec_value' => 'M3 Pro chip', 'display_order' => 3],
                ['spec_name' => 'Memory', 'spec_value' => '18GB Unified Memory', 'display_order' => 4],
                ['spec_name' => 'Storage', 'spec_value' => '512GB SSD', 'display_order' => 5],
                ['spec_name' => 'Display', 'spec_value' => '14.2" Liquid Retina XDR', 'display_order' => 6],
                ['spec_name' => 'Battery Life', 'spec_value' => 'Up to 18 hours', 'display_order' => 7],
                ['spec_name' => 'Weight', 'spec_value' => '1.6 kg', 'display_order' => 8],
                ['spec_name' => 'Warranty', 'spec_value' => '1 Year', 'display_order' => 9]
            ],
            11 => [ // AirPods Pro
                ['spec_name' => 'Brand', 'spec_value' => 'Apple', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'AirPods Pro (2nd gen)', 'display_order' => 2],
                ['spec_name' => 'Type', 'spec_value' => 'In-ear', 'display_order' => 3],
                ['spec_name' => 'Active Noise Cancellation', 'spec_value' => 'Yes', 'display_order' => 4],
                ['spec_name' => 'Battery Life', 'spec_value' => '6 hours (30h with case)', 'display_order' => 5],
                ['spec_name' => 'Charging Case', 'spec_value' => 'MagSafe + Lightning', 'display_order' => 6],
                ['spec_name' => 'Water Resistance', 'spec_value' => 'IPX4', 'display_order' => 7],
                ['spec_name' => 'Warranty', 'spec_value' => '1 Year', 'display_order' => 8]
            ],

            // Fashion Category (ID: 2)
            13 => [ // Chrome Hearts Hoodie
                ['spec_name' => 'Brand', 'spec_value' => 'Chrome Hearts', 'display_order' => 1],
                ['spec_name' => 'Material', 'spec_value' => '100% Cotton', 'display_order' => 2],
                ['spec_name' => 'Fit', 'spec_value' => 'Oversized', 'display_order' => 3],
                ['spec_name' => 'Care Instructions', 'spec_value' => 'Machine wash cold', 'display_order' => 4],
                ['spec_name' => 'Made In', 'spec_value' => 'USA', 'display_order' => 5],
                ['spec_name' => 'Sizes Available', 'spec_value' => 'S, M, L, XL, XXL', 'display_order' => 6],
                ['spec_name' => 'Weight', 'spec_value' => '650g', 'display_order' => 7]
            ],
            15 => [ // Hoodie Double Zip
                ['spec_name' => 'Brand', 'spec_value' => 'Nike', 'display_order' => 1],
                ['spec_name' => 'Material', 'spec_value' => '80% Cotton, 20% Polyester', 'display_order' => 2],
                ['spec_name' => 'Fit', 'spec_value' => 'Regular', 'display_order' => 3],
                ['spec_name' => 'Features', 'spec_value' => 'Double zipper, Kangaroo pocket', 'display_order' => 4],
                ['spec_name' => 'Care Instructions', 'spec_value' => 'Machine wash warm', 'display_order' => 5],
                ['spec_name' => 'Sizes Available', 'spec_value' => 'XS, S, M, L, XL', 'display_order' => 6]
            ],

            // Home & Garden Category (ID: 3)
            17 => [ // Smart Plant Watering System
                ['spec_name' => 'Brand', 'spec_value' => 'Xiaomi', 'display_order' => 1],
                ['spec_name' => 'Power Source', 'spec_value' => 'USB-C', 'display_order' => 2],
                ['spec_name' => 'Water Capacity', 'spec_value' => '2 Liters', 'display_order' => 3],
                ['spec_name' => 'Connectivity', 'spec_value' => 'WiFi, Bluetooth', 'display_order' => 4],
                ['spec_name' => 'App Control', 'spec_value' => 'Mi Home App', 'display_order' => 5],
                ['spec_name' => 'Sensors', 'spec_value' => 'Soil moisture, Light, Temperature', 'display_order' => 6],
                ['spec_name' => 'Material', 'spec_value' => 'ABS Plastic', 'display_order' => 7]
            ],
            19 => [ // Bamboo Cutting Board Set
                ['spec_name' => 'Material', 'spec_value' => '100% Organic Bamboo', 'display_order' => 1],
                ['spec_name' => 'Set Includes', 'spec_value' => '3 boards (Small, Medium, Large)', 'display_order' => 2],
                ['spec_name' => 'Dimensions', 'spec_value' => '8"x6", 12"x8", 15"x10"', 'display_order' => 3],
                ['spec_name' => 'Thickness', 'spec_value' => '0.6 inches', 'display_order' => 4],
                ['spec_name' => 'Features', 'spec_value' => 'Reversible, Juice groove', 'display_order' => 5],
                ['spec_name' => 'Care', 'spec_value' => 'Hand wash only', 'display_order' => 6],
                ['spec_name' => 'Eco-Friendly', 'spec_value' => 'Yes', 'display_order' => 7]
            ],

            // Gaming Category (ID: 4)
            21 => [ // RGB Mechanical Keyboard
                ['spec_name' => 'Brand', 'spec_value' => 'Corsair', 'display_order' => 1],
                ['spec_name' => 'Switch Type', 'spec_value' => 'Cherry MX Red', 'display_order' => 2],
                ['spec_name' => 'Layout', 'spec_value' => 'Full Size (104 keys)', 'display_order' => 3],
                ['spec_name' => 'Backlight', 'spec_value' => 'Per-key RGB', 'display_order' => 4],
                ['spec_name' => 'Connectivity', 'spec_value' => 'USB-A Wired', 'display_order' => 5],
                ['spec_name' => 'Polling Rate', 'spec_value' => '1000Hz', 'display_order' => 6],
                ['spec_name' => 'Material', 'spec_value' => 'Aluminum frame', 'display_order' => 7],
                ['spec_name' => 'Warranty', 'spec_value' => '2 Years', 'display_order' => 8]
            ],
            23 => [ // Gaming Chair Ergonomic
                ['spec_name' => 'Brand', 'spec_value' => 'SecretLab', 'display_order' => 1],
                ['spec_name' => 'Material', 'spec_value' => 'PU Leather', 'display_order' => 2],
                ['spec_name' => 'Weight Capacity', 'spec_value' => '130kg', 'display_order' => 3],
                ['spec_name' => 'Height Range', 'spec_value' => '165cm - 185cm', 'display_order' => 4],
                ['spec_name' => 'Armrests', 'spec_value' => '4D adjustable', 'display_order' => 5],
                ['spec_name' => 'Recline', 'spec_value' => '85° - 165°', 'display_order' => 6],
                ['spec_name' => 'Warranty', 'spec_value' => '3 Years', 'display_order' => 7]
            ],

            // Photography Category (ID: 5)
            25 => [ // Professional Camera Tripod
                ['spec_name' => 'Brand', 'spec_value' => 'Manfrotto', 'display_order' => 1],
                ['spec_name' => 'Material', 'spec_value' => 'Carbon Fiber', 'display_order' => 2],
                ['spec_name' => 'Max Height', 'spec_value' => '165cm', 'display_order' => 3],
                ['spec_name' => 'Min Height', 'spec_value' => '25cm', 'display_order' => 4],
                ['spec_name' => 'Weight Capacity', 'spec_value' => '8kg', 'display_order' => 5],
                ['spec_name' => 'Folded Length', 'spec_value' => '55cm', 'display_order' => 6],
                ['spec_name' => 'Weight', 'spec_value' => '1.8kg', 'display_order' => 7],
                ['spec_name' => 'Leg Sections', 'spec_value' => '4', 'display_order' => 8]
            ],
            26 => [ // LED Ring Light 18 inch
                ['spec_name' => 'Brand', 'spec_value' => 'Neewer', 'display_order' => 1],
                ['spec_name' => 'Diameter', 'spec_value' => '18 inches (45cm)', 'display_order' => 2],
                ['spec_name' => 'LED Count', 'spec_value' => '240 LEDs', 'display_order' => 3],
                ['spec_name' => 'Color Temperature', 'spec_value' => '3200K-5600K', 'display_order' => 4],
                ['spec_name' => 'Dimming', 'spec_value' => '1%-100%', 'display_order' => 5],
                ['spec_name' => 'Power', 'spec_value' => '55W', 'display_order' => 6],
                ['spec_name' => 'Stand Height', 'spec_value' => '78-200cm', 'display_order' => 7]
            ],

            // Audio Category (ID: 6)
            29 => [ // Studio Monitor Speakers
                ['spec_name' => 'Brand', 'spec_value' => 'KRK', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'Rokit 5 G4', 'display_order' => 2],
                ['spec_name' => 'Driver Size', 'spec_value' => '5" woofer, 1" tweeter', 'display_order' => 3],
                ['spec_name' => 'Frequency Response', 'spec_value' => '43Hz - 40kHz', 'display_order' => 4],
                ['spec_name' => 'Power', 'spec_value' => '55W total', 'display_order' => 5],
                ['spec_name' => 'Connectivity', 'spec_value' => 'XLR, TRS, RCA', 'display_order' => 6],
                ['spec_name' => 'Dimensions', 'spec_value' => '28.5 x 18.5 x 24.7 cm', 'display_order' => 7]
            ],
            31 => [ // USB Audio Interface
                ['spec_name' => 'Brand', 'spec_value' => 'Focusrite', 'display_order' => 1],
                ['spec_name' => 'Model', 'spec_value' => 'Scarlett 2i2 4th Gen', 'display_order' => 2],
                ['spec_name' => 'Inputs', 'spec_value' => '2 x XLR/TRS Combo', 'display_order' => 3],
                ['spec_name' => 'Outputs', 'spec_value' => '2 x TRS', 'display_order' => 4],
                ['spec_name' => 'Sample Rate', 'spec_value' => 'Up to 192kHz/24-bit', 'display_order' => 5],
                ['spec_name' => 'Connectivity', 'spec_value' => 'USB-C', 'display_order' => 6],
                ['spec_name' => 'Phantom Power', 'spec_value' => '+48V', 'display_order' => 7]
            ]
        ];

        foreach ($specifications as $productId => $specs) {
            foreach ($specs as $spec) {
                ProductSpecification::create([
                    'product_id' => $productId,
                    'spec_name' => $spec['spec_name'],
                    'spec_value' => $spec['spec_value'],
                    'display_order' => $spec['display_order']
                ]);
            }
        }
    }
}