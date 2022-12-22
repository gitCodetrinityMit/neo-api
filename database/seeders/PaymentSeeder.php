<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment = [
            [
                'payment_method'    =>  'COD',
                'user_id'   =>  1,
                'order_id'  =>  1,
                'product_id'  =>  1,
                'transaction_id'    =>  NULL,
                'amount'    =>  1000,
                'payment_amount'   => 2000,
                'payer_email'   =>  'payer@gmail.com',
                'payment_status'    =>  2
            ],
            [
                'payment_method'    =>  'COD',
                'user_id'   =>  1,
                'order_id'  =>  1,
                'product_id'  =>  2,
                'transaction_id'    =>  NULL,
                'amount'    =>  1000,
                'payment_amount'   => 2000,
                'payer_email'   =>  'payer1@gmail.com',
                'payment_status'    =>  2
            ]
        ];

        foreach ($payment as $key => $payments) {
            Payment::create($payments);
        }
    }
}
