<?php

use App\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 5 transactions and ensure clients are created if they don't exist
        factory(Transaction::class, 5)->create();
    }
}
