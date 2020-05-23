<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TurnAssetUserRelationIntoMorphRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign('assets_user_id_foreign');
            $table->renameColumn('user_id', 'owner_id');
            $table->string('owner_type')->default('user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->renameColumn('owner_id', 'user_id');
            $table->dropColumn('owner_type');
        });
    }
}
