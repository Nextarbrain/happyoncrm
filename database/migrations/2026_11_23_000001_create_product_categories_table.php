<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('colour', 20)->default('#0A5F59');
                $table->string('icon')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedBigInteger('branch_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
