<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransformBallotSubjectsToOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('ballot_subjects', 'ballot_items');
        Schema::create('ballot_item_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vote_count')->default(0);
            $table->integer('subject_id');
            $table->string('subject_type');
            $table->timestamps();
        });
        // TODO migrate old data and remove duplicate fields
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ballot_item_options');
    }
}
