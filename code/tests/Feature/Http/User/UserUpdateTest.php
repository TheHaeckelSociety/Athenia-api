<?php
declare(strict_types=1);

namespace Tests\Feature\Http\User;

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class UserUpdateTest
 * @package Tests\Feature\Http\User
 */
class UserUpdateTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var string
     */
    private $path = '/v1/users';

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();
    }

    public function testNotLoggedInUserBlocked()
    {
        $character = factory(User::class)->create();
        $response = $this->json('PUT', $this->path . '/' . $character->id);

        $response->assertStatus(403);
    }

    public function testDifferentUserBlocked()
    {
        $this->actAsUser();
        $user = factory(User::class)->create();

        $response = $this->json('PUT', $this->path . '/' . $user->id);

        $response->assertStatus(403);
    }

    public function testNotFound()
    {
        $this->actAsUser();

        $response = $this->json('PUT', $this->path . '/1');

        $response->assertStatus(404);
    }

    public function testUpdateSuccessful()
    {
        $this->actAsUser();

        $data = [
            'name' => 'Lance',
            'email' => 'test@test.com',
        ];

        $response = $this->json('PUT', $this->path . '/' . $this->actingAs->id, $data);

        $response->assertStatus(200);
        $response->assertJson($data);

        /** @var User $updated */
        $updated = User::find($this->actingAs->id);

        $this->assertEquals('Lance', $updated->name);
    }

    public function testUpdatePasswordSuccessful()
    {
        $this->actAsUser([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        $data = [
            'password' => 'superSecret',
        ];

        $response = $this->json('PUT', $this->path . '/' . $this->actingAs->id, $data);

        $response->assertStatus(200);

        $loginResponse = $this->json('POST', '/v1/auth/login', [
            'email' => 'test@test.com',
            'password' => 'superSecret',
        ]);

        $loginResponse->assertStatus(200);
    }

    public function testUpdateFailsInvalidStringFields()
    {
        $this->actAsUser();

        $response = $this->json('PUT', $this->path . '/' . $this->actingAs->id, [
            'email' => 1,
            'name' => 1,
            'password' => 1,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                'email' => ['The email must be a string.'],
                'name' => ['The name must be a string.'],
                'password' => ['The password must be a string.'],
            ]
        ]);
    }

    public function testUpdateFailsInvalidEmail()
    {
        $this->actAsUser();

        $response = $this->json('PUT', $this->path . '/' . $this->actingAs->id, [
            'email' => 'owriowf',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                'email' => ['The email must be a valid email address.'],
            ]
        ]);
    }

    public function testUpdateFailsStringLengthWrong()
    {
        $this->actAsUser();

        $response = $this->json('PUT', $this->path . '/' . $this->actingAs->id, [
            'password' => str_repeat('a', 5),
            'email' => str_repeat('a', 121),
            'name' => str_repeat('a', 121),
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                'password' => ['The password must be at least 6 characters.'],
                'email' => ['The email may not be greater than 120 characters.'],
                'name' => ['The name may not be greater than 120 characters.'],
            ]
        ]);
    }
}