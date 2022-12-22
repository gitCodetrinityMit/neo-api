<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = [
            [
                'user_id'   =>  1,
                'shipping_price' => 2500,
                'payment_status' => 1,
                'order_status' => 1,
                'order_number' => '#100001',
                'total_price' => 1000,
                'payment_method' => 'COD',
                'shippping_address' => 'User Address'  
            ],
        ];

        foreach ($orders as $key => $order) {
            Order::create($order);
        }

        $product_orders = [
            [
                'order_id'  =>  1,
                'product_id'    =>  1,
                'product_name'  =>  'Test Order Product',
                'product_quantity' => 2,
                'product_slug'  =>  'test-order-product',
                'unit_price'   =>  2500,
                'product_total_price' => 5000,
            ]
        ];

        foreach ($product_orders as $key => $product_order) {
            OrderProducts::create($product_order);
        }
    }
}
