<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipBuysIdToMembershipBuyRenewableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_buy_renewable', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_buys_id')->nullable()->after('id');
            $table->foreign('membership_buys_id')->references('id')->on('membership_buys')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_buy_renewable', function (Blueprint $table) {
            $table->dropForeign(['membership_buys_id']);
            $table->dropColumn('membership_buys_id');
        });
    }
}
