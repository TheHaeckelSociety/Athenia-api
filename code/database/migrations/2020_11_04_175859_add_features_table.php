<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('feature_membership_plan', function (Blueprint $table) {
            $table->unsignedInteger('feature_id');
            $table->foreign('feature_id')->references('id')->on('features');
            $table->unsignedInteger('membership_plan_id');
            $table->foreign('membership_plan_id')->references('id')->on('membership_plans');
            $table->primary(['feature_id', 'membership_plan_id']);
        });
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('entity_type')->default('user');
            $table->boolean('default')->default(false);
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
            $table->dropColumn('description');
            $table->dropColumn('entity_type');
            $table->dropColumn('default');
        });
        Schema::dropIfExists('feature_membership_plan');
        Schema::dropIfExists('features');
    }
}
