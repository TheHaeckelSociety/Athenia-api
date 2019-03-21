<?php
declare(strict_types=1);

namespace Tests\Integration\Models\Wiki;

use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use Carbon\Carbon;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class ArticleTest
 * @package Tests\Integration\Models\Wiki
 */
class ArticleTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
    }

    public function testContentReturnsNull()
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        $this->assertNull($article->content);
    }

    public function testContentReturnsModelContent()
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        /** @var Iteration $iteration This should be appended */
        factory(Iteration::class)->create([
            'article_id' => $article->id,
            'content' => 'Hello'
        ]);

        $this->assertEquals('Hello', $article->content);
    }

    public function testContentReturnsCorrectModel()
    {
        /** @var Article $article */
        $article = factory(Article::class)->create();

        /** This should be appended */
        factory(Iteration::class)->create([
            'article_id' => $article->id,
            'created_at' => Carbon::now(),
            'content' => 'Hello'
        ]);

        /** This is an old iteration that should not be appended */
        factory(Iteration::class)->create([
            'article_id' => $article->id,
            'created_at' => Carbon::now()->subDay(),
            'content' => 'old content'
        ]);

        $this->assertEquals('Hello', $article->content);
    }
}