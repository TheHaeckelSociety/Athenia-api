<?php
namespace Database\Factories\Vote;

use App\Models\Vote\BallotCompletion;
use App\Models\Vote\BallotSubject;
use App\Models\Vote\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AssetFactory
 * @package Database\Factories
 */
class VoteFactory extends Factory
{
    /**
     * @var string The related model
     */
    protected $model = Vote::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'ballot_completion_id' => BallotCompletion::factory()->create()->id,
            'ballot_subject_id' => BallotSubject::factory()->create()->id,
        ];
    }
}
