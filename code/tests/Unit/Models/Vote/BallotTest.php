<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Vote;

use App\Models\Vote\Ballot;
use Tests\TestCase;

/**
 * Class BallotTest
 * @package Tests\Unit\Models\Vote
 */
class BallotTest extends TestCase
{
    public function testBallotCompletions()
    {
        $model = new Ballot();
        $relation = $model->ballotCompletions();

        $this->assertEquals('ballots.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('ballot_completions.ballot_id', $relation->getQualifiedForeignKeyName());
    }

    public function testBallotItems()
    {
        $model = new Ballot();
        $relation = $model->ballotItems();

        $this->assertEquals('ballots.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('ballot_items.ballot_id', $relation->getQualifiedForeignKeyName());
    }
}
