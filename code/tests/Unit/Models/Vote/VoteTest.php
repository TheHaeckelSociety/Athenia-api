<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Vote;

use App\Models\Vote\Vote;
use Tests\TestCase;

/**
 * Class VoteTest
 * @package Tests\Unit\Models\Vote
 */
class VoteTest extends TestCase
{
    public function testBallotCompletion()
    {
        $model = new Vote();
        $relation = $model->ballotCompletion();

        $this->assertEquals('ballot_completions.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('votes.ballot_completion_id', $relation->getQualifiedForeignKeyName());
    }

    public function testBallotItemOption()
    {
        $model = new Vote();
        $relation = $model->ballotItemOption();

        $this->assertEquals('ballot_item_options.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('votes.ballot_item_option_id', $relation->getQualifiedForeignKeyName());
    }
}
