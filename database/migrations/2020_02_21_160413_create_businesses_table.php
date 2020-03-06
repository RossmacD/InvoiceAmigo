<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('business_name');
            $table->string('website');
            $table->string('address');
            $table->string('country');
            $table->string('postcode');
            $table->string("stripe_id")->nullable();
            // $table->unsignedBigInteger('user_id'); // unsigned for foreign key
            // $table->foreign('user_id') // foreign key column name.
            // ->references('id') // parent table primary key.
            // ->on('users') // parent table name.
            // ->onDelete('cascade'); // this will delete all the children rows when the parent row is deleted.
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
        Schema::dropIfExists('businesses');
    }
}
