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
        Schema::create('payment_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_type', ['deposit', 'withdraw']);
            $table->enum('client_type', ['private', 'business']);
            $table->float('fee', 8, 2)->default(0.00);
            $table->integer('free_of_charge');
            $table->float('free_of_charge_amount', 8, 2);
            $table->index(['payment_type', 'client_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_rules');
    }
};
