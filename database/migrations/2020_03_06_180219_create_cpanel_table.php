<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpanel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('whm_username');
            $table->string('api_token');
            $table->string('hostname');
            $table->string('port');
            $table->unsignedBigInteger('business_id');
            $table->foreign('business_id') // foreign key column name.
            ->references('id') // parent table primary key.
            ->on('businesses') // parent table name.
            ->onDelete('cascade'); // this will delete all the children rows when the parent row is deleted.
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
        Schema::dropIfExists('cpanel');
    }
}
