<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToModels extends Migration
{
    /**
     * The models that require soft deletes.
     *
     * @var array
     */
    protected $models = [
        'users',
        'membership_plans',
        'membership_histories',
        'membership_buys',
        'event_items',
        'event_bookings',
        'open_billings',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->models as $model) {
            Schema::table($model, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->models as $model) {
            Schema::table($model, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
