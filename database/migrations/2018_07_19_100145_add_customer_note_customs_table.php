<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerNoteCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customs', function (Blueprint $table) {
            $table->text('customer_note')->nullable()->comment('客户备注')->after('identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customs', function (Blueprint $table) {
            $table->dropColumn('customer_note');
        });
    }
}
