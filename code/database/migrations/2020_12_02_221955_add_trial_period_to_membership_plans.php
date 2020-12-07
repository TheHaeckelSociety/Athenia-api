<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrialPeriodToMembershipPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->integer('trial_period')->nullable();
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropColumn('trial_period');
        });
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('is_trial');
        });
    }
}
