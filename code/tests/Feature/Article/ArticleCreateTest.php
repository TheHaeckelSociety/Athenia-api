<?php
declare(strict_types=1);

namespace Tests\Feature\Article;

use App\Models\User\User;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class ArticleCreateTest
 * @package Tests\Feature\Article
 */
class ArticleCreateTest extends TestCase
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

    public function testNotLoggedInUserBlocked()
    {
        $response = $this->json('POST', $this->path);

        $response->assertStatus(403);
    }

    public function testCreateSuccessful()
    {
        $this->actAsUser();

        $user = factory(User::class)->create();

        $response = $this->json('POST', $this->path, [
            'title' => 'An Article',
            'created_by_id' => $user->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'title' => 'An Article',
            'created_by_id' => $user->id,
            'content' => '',
        ]);
    }

    public function testCreateFailsRequiredFieldsNotPresent()
    {
        $this->actAsUser();

        $response = $this->json('POST', $this->path);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'created_by_id' => ['The created by id field is required.'],
                'title' => ['The title field is required.'],
            ]
        ]);
    }

    public function testCreateFailsInvalidStringFields()
    {
        $this->actAsUser();

        $response = $this->json('POST', $this->path, [
            'title' => 1,
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'title' => ['The title must be a string.'],
            ]
        ]);
    }

    public function testCreateFailsInvalidIntegerFields()
    {
        $this->actAsUser();

        $response = $this->json('POST', $this->path, [
            'created_by_id' => 'hi',
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'created_by_id' => ['The created by id must be an integer.'],
            ]
        ]);
    }

    public function testCreateFailsStringsTooLong()
    {
        $this->actAsUser();

        $response = $this->json('POST', $this->path, [
            'title' => str_repeat('a', 121),
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'title' => ['The title may not be greater than 120 characters.'],
            ]
        ]);
    }

    public function testCreateFailsInvalidModels()
    {
        $this->actAsUser();

        $response = $this->json('POST', $this->path, [
            'created_by_id' => 435,
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'created_by_id' => ['The selected created by id is invalid.'],
            ]
        ]);
    }
}