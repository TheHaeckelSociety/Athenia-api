<?php
namespace Database\Factories\Vote;

use App\Models\Vote\Ballot;
use App\Models\Vote\BallotSubject;
use App\Models\Wiki\Iteration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AssetFactory
 * @package Database\Factories
 */
class BallotSubjectFactory extends Factory
{
    /**
     * @var string The related model
     */
    protected $model = BallotSubject::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'ballot_id' => Ballot::factory()->create()->id,
            'subject_id' => Iteration::factory()->create()->id,
            'subject_type' => 'iteration',
        ];
    }
}
