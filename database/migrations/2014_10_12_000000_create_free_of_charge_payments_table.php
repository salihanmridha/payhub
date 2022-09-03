<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_of_charge_payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->integer('client_id');
            $table->enum('payment_type', ['deposit', 'withdraw']);
            $table->float('amount', 8, 2);
            $table->char('currency', 3);
            $table->timestamps();
            $table->index(['payment_date']);
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_of_charge_payments');
    }
};
