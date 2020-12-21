<?php
declare(strict_types=1);

namespace Database\Factories\Wiki;

use App\Models\User\User;
use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ArticleFactory
 * @package Database\Factories\Wiki
 */
class ArticleFactory extends Factory
{
    /**
     * @var string The related model
     */
    protected $model = Article::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'created_by_id' => User::factory()->create()->id,
        ];
    }
}
