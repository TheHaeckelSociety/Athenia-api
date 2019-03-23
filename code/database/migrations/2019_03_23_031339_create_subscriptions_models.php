<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 40);

            $table->string('duration', 20);

            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('membership_plan_rates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('membership_plan_id');
            $table->foreign('membership_plan_id')->references('id')->on('membership_plans');

            $table->float('cost');

            $table->boolean('active');

            $table->softDeletes();
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
        Schema::dropIfExists('membership_plan_rates');
        Schema::dropIfExists('membership_plans');
    }
}
