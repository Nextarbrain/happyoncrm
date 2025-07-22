<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToDayPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('day_prices', function (Blueprint $table) {
           $table->unsignedBigInteger('branch_id')->nullable()->after('id');
           $table->foreign('branch_id')->references('id')->on('branch')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('day_prices', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
             $table->dropColumn('branch_id');
        });
    }
}
