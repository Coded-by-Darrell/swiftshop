<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductAttributeValue;
use Carbon\Carbon;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        // Get attribute values
        $colors = ProductAttributeValue::whereHas('attribute', fn($q) => $q->where('name', 'Color'))->get();
        $sizes = ProductAttributeValue::whereHas('attribute', fn($q) => $q->where('name', 'Size'))->get();
        $storage = ProductAttributeValue::whereHas('attribute', fn($q) => $q->where('name', 'Storage'))->get();

        // Get some products to add variants to
        $products = Product::take(10)->get();

        foreach ($products as $index => $product) {
            $this->createVariantsForProduct($product, $colors, $sizes, $storage, $index);
        }
    }

    private function createVariantsForProduct($product, $colors, $sizes, $storage, $productIndex)
    {
        $variants = [];

        switch ($product->name) {
            case 'Wireless Headphones':
                $variants = [
                    [
                        'sku' => 'WH-001-BLK',
                        'original_price' => 79.99,
                        'sale_price' => 59.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(5),
                        'sale_end_date' => Carbon::now()->addDays(10),
                        'stock_quantity' => 25,
                        'is_default' => true,
                        'attributes' => ['Black']
                    ],
                    [
                        'sku' => 'WH-001-WHT',
                        'original_price' => 79.99,
                        'sale_price' => 59.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(5),
                        'sale_end_date' => Carbon::now()->addDays(10),
                        'stock_quantity' => 25,
                        'attributes' => ['White']
                    ]
                ];
                break;

                case 'USB-C Cable':
                    $variants = [
                        [
                            'sku' => 'USBC-007-BLK',
                            'original_price' => 12.99,
                            'sale_price' => 9.99,
                            'is_on_sale' => true,
                            'sale_start_date' => Carbon::now()->subDays(2),
                            'sale_end_date' => Carbon::now()->addDays(12),
                            'stock_quantity' => 50,
                            'is_default' => true,
                            'attributes' => ['Black']
                        ],
                        [
                            'sku' => 'USBC-007-WHT',
                            'original_price' => 12.99,
                            'sale_price' => 9.99,
                            'is_on_sale' => true,
                            'sale_start_date' => Carbon::now()->subDays(2),
                            'sale_end_date' => Carbon::now()->addDays(12),
                            'stock_quantity' => 50,
                            'attributes' => ['White']
                        ]
                    ];
                    break;

            case 'Smart Watch':
                $variants = [
                    [
                        'sku' => 'SW-002-BLK',
                        'original_price' => 199.99,
                        'stock_quantity' => 15,
                        'is_default' => true,
                        'attributes' => ['Black']
                    ],
                    [
                        'sku' => 'SW-002-SLV',
                        'original_price' => 199.99,
                        'stock_quantity' => 10,
                        'attributes' => ['Silver']
                    ],
                    [
                        'sku' => 'SW-002-GLD',
                        'original_price' => 219.99,
                        'stock_quantity' => 5,
                        'attributes' => ['Gold']
                    ]
                ];
                break;

            case 'Gaming Mouse':
                $variants = [
                    [
                        'sku' => 'GM-003-BLK',
                        'original_price' => 39.99,
                        'sale_price' => 29.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(3),
                        'sale_end_date' => Carbon::now()->addDays(7),
                        'stock_quantity' => 40,
                        'is_default' => true,
                        'attributes' => ['Black']
                    ],
                    [
                        'sku' => 'GM-003-RED',
                        'original_price' => 39.99,
                        'sale_price' => 29.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(3),
                        'sale_end_date' => Carbon::now()->addDays(7),
                        'stock_quantity' => 35,
                        'attributes' => ['Red']
                    ]
                ];
                break;

            case 'Chrome Hearts Hoodie':
                $variants = [
                    [
                        'sku' => 'CHH-004-BLK-S',
                        'original_price' => 17999.99,
                        'stock_quantity' => 1,
                        'is_default' => true,
                        'attributes' => ['Black', 'S']
                    ],
                    [
                        'sku' => 'CHH-004-BLK-M',
                        'original_price' => 17999.99,
                        'stock_quantity' => 2,
                        'attributes' => ['Black', 'M']
                    ],
                    [
                        'sku' => 'CHH-004-BLK-L',
                        'original_price' => 17999.99,
                        'stock_quantity' => 1,
                        'attributes' => ['Black', 'L']
                    ],
                    [
                        'sku' => 'CHH-004-WHT-M',
                        'original_price' => 17999.99,
                        'stock_quantity' => 1,
                        'attributes' => ['White', 'M']
                    ]
                ];
                break;

            case 'MacBook Pro':
                $variants = [
                    [
                        'sku' => 'MBP-005-SLV-256',
                        'original_price' => 1999.99,
                        'stock_quantity' => 5,
                        'is_default' => true,
                        'attributes' => ['Silver', '256GB']
                    ],
                    [
                        'sku' => 'MBP-005-SLV-512',
                        'original_price' => 2299.99,
                        'stock_quantity' => 3,
                        'attributes' => ['Silver', '512GB']
                    ],
                    [
                        'sku' => 'MBP-005-GRY-256',
                        'original_price' => 1999.99,
                        'stock_quantity' => 2,
                        'attributes' => ['Gray', '256GB']
                    ]
                ];
                break;

            case 'iPhone 15 Case':
                $variants = [
                    [
                        'sku' => 'IP15C-006-BLK',
                        'original_price' => 29.99,
                        'sale_price' => 19.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(1),
                        'sale_end_date' => Carbon::now()->addDays(14),
                        'stock_quantity' => 30,
                        'is_default' => true,
                        'attributes' => ['Black']
                    ],
                    [
                        'sku' => 'IP15C-006-BLU',
                        'original_price' => 29.99,
                        'sale_price' => 19.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(1),
                        'sale_end_date' => Carbon::now()->addDays(14),
                        'stock_quantity' => 25,
                        'attributes' => ['Blue']
                    ],
                    [
                        'sku' => 'IP15C-006-RED',
                        'original_price' => 29.99,
                        'sale_price' => 19.99,
                        'is_on_sale' => true,
                        'sale_start_date' => Carbon::now()->subDays(1),
                        'sale_end_date' => Carbon::now()->addDays(14),
                        'stock_quantity' => 25,
                        'attributes' => ['Red']
                    ]
                ];
                break;

            default:
                // For other products, create simple color variants
                $variants = [
                    [
                        'sku' => 'PROD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) . '-BLK',
                        'original_price' => $product->price,
                        'stock_quantity' => ceil($product->stock_quantity / 2),
                        'is_default' => true,
                        'attributes' => ['Black']
                    ],
                    [
                        'sku' => 'PROD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT) . '-WHT',
                        'original_price' => $product->price,
                        'stock_quantity' => floor($product->stock_quantity / 2),
                        'attributes' => ['White']
                    ]
                ];
                break;
        }

        foreach ($variants as $variantData) {
            $attributes = $variantData['attributes'];
            unset($variantData['attributes']);

            $variant = ProductVariant::create(array_merge([
                'product_id' => $product->id,
                'weight' => rand(100, 1000) / 100, // Random weight between 1-10kg
                'dimensions' => [
                    'length' => rand(10, 50),
                    'width' => rand(10, 50),
                    'height' => rand(5, 20)
                ],
                'barcode' => '12345' . str_pad($product->id, 3, '0', STR_PAD_LEFT) . rand(1000, 9999),
                'low_stock_threshold' => 5
            ], $variantData));

            // Attach attribute values
            foreach ($attributes as $attributeValue) {
                $attrValue = ProductAttributeValue::where('value', $attributeValue)->first();
                if ($attrValue) {
                    $variant->attributeValues()->attach($attrValue->id);
                }
            }
        }
    }
}