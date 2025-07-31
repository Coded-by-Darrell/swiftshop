<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;

class OrderHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);
        }

        // Get some products and vendors
        $products = Product::with('vendor')->take(10)->get();
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        // Create sample orders with different statuses
        $this->createSampleOrders($user, $products);
    }

    private function createSampleOrders($user, $products)
    {
        $statuses = ['pending', 'processing', 'delivered', 'cancelled'];
        $orderData = [
            [
                'status' => 'delivered',
                'days_ago' => 15,
                'products_count' => 3
            ],
            [
                'status' => 'processing',
                'days_ago' => 5,
                'products_count' => 2
            ],
            [
                'status' => 'pending',
                'days_ago' => 1,
                'products_count' => 1
            ],
            [
                'status' => 'delivered',
                'days_ago' => 30,
                'products_count' => 4
            ],
            [
                'status' => 'cancelled',
                'days_ago' => 10,
                'products_count' => 2
            ],
        ];

        foreach ($orderData as $index => $data) {
            $this->createOrder($user, $products, $data, $index + 1);
        }
    }

    private function createOrder($user, $products, $data, $orderNumber)
    {
        // Calculate dates
        $createdAt = Carbon::now()->subDays($data['days_ago']);
        $deliveredAt = $data['status'] === 'delivered' ? $createdAt->copy()->addDays(rand(2, 5)) : null;

        // Calculate order totals
        $selectedProducts = $products->random($data['products_count']);
        $subtotal = 0;
        $items = [];

        foreach ($selectedProducts as $product) {
            $quantity = rand(1, 3);
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $quantity;
            $subtotal += $totalPrice;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice
            ];
        }

        $shippingFee = 95.00; // Standard shipping fee
        $taxAmount = $subtotal * 0.12; // 12% tax
        $totalAmount = $subtotal + $shippingFee + $taxAmount;

        // Create the order
        $order = Order::create([
            'order_number' => 'SW-2024-' . str_pad($orderNumber, 6, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '+63 917 123 4567',
            'shipping_address' => json_encode([
                'full_name' => $user->name,
                'street_address' => '123 Main Street, Apt 4B',
                'city' => 'Batangas City',
                'postal_code' => '4200',
                'phone' => '+63 917 123 4567'
            ]),
            'shipping_method' => 'standard',
            'order_notes' => $this->getRandomOrderNote(),
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_method' => 'COD',
            'status' => $data['status'],
            'estimated_delivery' => $createdAt->copy()->addDays(5),
            'delivered_at' => $deliveredAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        // Create order items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'vendor_id' => $item['product']->vendor_id,
                'variant_id' => null,
                'product_name' => $item['product']->name,
                'vendor_name' => $item['product']->vendor->business_name,
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
                'product_snapshot' => json_encode([
                    'name' => $item['product']->name,
                    'description' => $item['product']->description,
                    'price' => $item['product']->price,
                    'vendor' => $item['product']->vendor->business_name,
                ]),
                'status' => $data['status'],
                'vendor_confirmed_at' => in_array($data['status'], ['processing', 'delivered']) ? $createdAt->copy()->addHours(2) : null,
                'shipped_at' => in_array($data['status'], ['delivered']) ? $createdAt->copy()->addDays(2) : null,
                'delivered_at' => $deliveredAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info("Created order {$order->order_number} with status: {$data['status']}");
    }

    private function getRandomOrderNote()
    {
        $notes = [
            'Please call before delivery.',
            'Leave at front door if no one is home.',
            'Handle with care - fragile items.',
            'Deliver during weekday hours only.',
            null, // Some orders have no notes
            'Contact me via SMS for delivery updates.',
        ];

        return $notes[array_rand($notes)];
    }
}