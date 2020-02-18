<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_number');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['unseen','unpaid','paid']);
            $table->enum('currency', ['eur', 'gbp','usd']);
            $table->bigInteger('total_cost');
            $table->text('note')->nullable();
            $table->bigInteger('client_id');
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
        Schema::dropIfExists('invoices');
    }
}
