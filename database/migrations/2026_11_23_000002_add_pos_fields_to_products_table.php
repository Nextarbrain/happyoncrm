<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPosFieldsToProductsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'image_path')) {
                    $table->string('image_path')->nullable()->after('product_description');
                }
                if (!Schema::hasColumn('products', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('image_path');
                }
                if (!Schema::hasColumn('products', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0)->after('category_id');
                }
                if (!Schema::hasColumn('products', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('sort_order');
                }
                if (!Schema::hasColumn('products', 'sku')) {
                    $table->string('sku', 64)->nullable()->after('is_active');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('products')) {
            $cols = [];
            foreach (['image_path', 'category_id', 'sort_order', 'is_active', 'sku'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                // MySQL supports native dropColumn; SQLite without doctrine/dbal throws on dropColumn
                if (Schema::getConnection()->getDriverName() !== 'sqlite' || class_exists('Doctrine\DBAL\Driver\AbstractSQLiteDriver')) {
                    Schema::table('products', function (Blueprint $table) use ($cols) {
                        $table->dropColumn($cols);
                    });
                }
            }
        }
    }
}
