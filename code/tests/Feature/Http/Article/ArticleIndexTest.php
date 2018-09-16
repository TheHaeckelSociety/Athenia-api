<?php
declare(strict_types=1);

namespace Tests\Feature\HttpArticle;

use App\Models\Wiki\Article;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class ArticleIndexTest
 * @package Tests\Feature\HttpArticle
 */
class ArticleIndexTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var string
     */
    private $path = '/v1/articles';

    public function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();
    }

    public function testNotLoggedUserBlocked()
    {
        $response = $this->json('GET', $this->path);

        $response->assertStatus(403);
    }

    public function testGetPaginationEmpty()
    {
        $this->actAsUser();
        $response = $this->json('GET', $this->path);

        $response->assertStatus(200);
        $response->assertJson([
            'total' => 0,
            'data' => []
        ]);
    }

    public function testGetPaginationResult()
    {
        $this->actAsUser();
        factory(Article::class, 15)->create();

        // first page
        $response = $this->json('GET', $this->path);
        $response->assertStatus(200);
        $response->assertJson([
            'total' => 15,
            'current_page' => 1,
            'per_page' => 10,
            'from' => 1,
            'to' => 10,
            'last_page' => 2
        ])
            ->assertJsonStructure([
                'data' => [
                    '*' =>  array_keys((new Article())->toArray())
                ]
            ]);

        // second page
        $response = $this->json('GET', $this->path . '?page=2');
        $response->assertStatus(200);
        $response->assertJson([
            'total' =>  15,
            'current_page' => 2,
            'per_page' => 10,
            'from' => 11,
            'to' => 15,
            'last_page' => 2
        ])
            ->assertJsonStructure([
                'data' => [
                    '*' =>  array_keys((new Article())->toArray())
                ]
            ]);

        // page with limit
        $response = $this->json('GET', $this->path . '?page=2&limit=5');
        $response->assertStatus(200);
        $response->assertJson([
            'total' =>  15,
            'current_page' => 2,
            'per_page' => 5,
            'from' => 6,
            'to' => 10,
            'last_page' => 3
        ])
            ->assertJsonStructure([
                'data' => [
                    '*' =>  array_keys((new Article())->toArray())
                ]
            ]);
    }
}