<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id'); // Define client_id as an unsigned integer
            $table->foreign('client_id')          // Add foreign key constraint
            ->references('id')              // References the id on the clients table
            ->on('clients')                 // On the clients table
            ->onDelete('cascade');          // Cascade on delete

            $table->date('transaction_date');
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
