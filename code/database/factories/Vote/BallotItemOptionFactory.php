<?php
namespace Database\Factories\Vote;

use App\Models\Vote\BallotItem;
use App\Models\Vote\BallotItemOption;
use App\Models\Wiki\Iteration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class BallotItemOptionFactory
 * @package Database\Factories
 */
class BallotItemOptionFactory extends Factory
{
    /**
     * @var string The related model
     */
    protected $model = BallotItemOption::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'ballot_item_id' => BallotItem::factory()->create()->id,
            'subject_id' => Iteration::factory()->create()->id,
            'subject_type' => 'iteration',
        ];
    }
}
