<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Invoice;

class AddUserIdColumnAndForeignConstraintToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            Invoice::truncate(); // empty the table
            Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('client_id'); //drop exisiting client_id column
            $table->unsignedBigInteger('user_id')->nullable(); // unsigned for foreign key.
            $table->foreign('user_id') // foreign key column name.
            ->references('id') // parent table primary key.
            ->on('users') // parent table name.
            ->onDelete('cascade'); // this will delete all the children rows when the parent row is deleted.
    });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->bigInteger('client_id');
        });
    }
}
