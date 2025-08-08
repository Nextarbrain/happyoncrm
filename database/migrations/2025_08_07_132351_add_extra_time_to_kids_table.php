<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraTimeToKidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kids', function (Blueprint $table) {
            $table->string('extra_hour', 50)->after('kidPrice')->nullable();
            $table->string('extra_price', 50)->after('extra_hour')->nullable();
            $table->string('qty', 50)->after('extra_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kids', function (Blueprint $table) {
            $table->dropColumn(['extra_hour', 'extra_price', 'qty']);
        });
    }
}
