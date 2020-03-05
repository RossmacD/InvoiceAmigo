<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Invoice;

class AddDraftEmailToInvoicesTable extends Migration
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
            $table->string('draft_email')->nullable(); //Add draft email column for draft invoices
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
            $table->dropColumn('draft_email');
        });
    }
}
