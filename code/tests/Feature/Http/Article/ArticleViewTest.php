<?php
declare(strict_types=1);

namespace Tests\Feature\HttpArticle;

use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class ArticleViewTest
 * @package Tests\Feature\HttpArticle
 */
class ArticleViewTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var string
     */
    private $path = '/v1/articles/';

    /**
     * @var Article
     */
    private $article;

    public function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();

        $this->article = factory(Article::class)->create();
        $this->path.= $this->article->id;
    }

    public function testNotLoggedInUserBlocked()
    {
        $response = $this->json('GET', $this->path);

        $response->assertStatus(403);
    }

    public function testNotFound()
    {
        $this->actAsUser();

        $response = $this->json('GET',   '/v1/articles/1435');

        $response->assertStatus(404);
    }

    public function testViewSuccessful()
    {
        $this->actAsUser();

        factory(Iteration::class)->create([
            'content' => 'hello',
            'article_id' => $this->article->id,
        ]);

        $response = $this->json('GET', $this->path);

        $response->assertStatus(200);

        $response->assertJson($this->article->toArray());

        $this->assertNotNull($response->json()['content']);
        $this->assertEquals($this->article->content, $response->json()['content']);
    }
}