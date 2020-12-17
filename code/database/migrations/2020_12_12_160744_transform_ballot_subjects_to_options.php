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
            $table->unsignedInteger('ballot_item_id');
            $table->foreign('ballot_item_id')->references('id')->on('ballot_items');
            $table->integer('vote_count')->default(0);
            $table->integer('subject_id');
            $table->string('subject_type');
            $table->softDeletes();
            $table->timestamps();
        });
        foreach (\App\Models\Vote\BallotItem::withTrashed()->get() as $ballotItem) {
            $ballotItemOption = new \App\Models\Vote\BallotItemOption([
                'ballot_item_id' => $ballotItem->id,
                'vote_count' => $ballotItem->vote_count,
                'subject_type' => $ballotItem->subject_type,
                'subject_id' => $ballotItem->subject_id,
            ]);
            $ballotItemOption->id = $ballotItem->id;

            $ballotItemOption->save();
        }
        Schema::table('ballot_items', function(Blueprint $table) {
            $table->dropColumn('subject_id');
            $table->dropColumn('subject_type');
            $table->dropColumn('vote_count');
        });
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign('votes_ballot_subject_id_foreign');
            $table->renameColumn('ballot_subject_id', 'ballot_item_option_id');
            $table->foreign('ballot_item_option_id')->references('id')->on('ballot_item_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {}
}
