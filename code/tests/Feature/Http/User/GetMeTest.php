<?php
declare(strict_types=1);

namespace Tests\Feature\Http\User;

use App\Contracts\Services\StripeCustomerServiceContract;
use App\Models\User\User;
use Exception;
use Mockery;
use Tests\CustomMockInterface;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class GetMeTest
 * @package Tests\Feature\Http\User
 */
class GetMeTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();
    }

    public function testGetMeSuccess()
    {
        $myCurrentUser = factory(User::class)->create();

        $this->actingAs($myCurrentUser);

        $response = $this->json('GET', '/v1/users/me');
        $response->assertExactJson($myCurrentUser->toArray());
        $response->assertStatus(200);
    }

    public function testGetMeFailsWithTooManyExpands()
    {
        $myCurrentUser = factory(User::class)->create();

        $this->actingAs($myCurrentUser);

        $response = $this->json('GET', '/v1/users/me?expand[roles.users]=*');

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Sorry, something went wrong.',
            'details' => 'The relation roles.users cannot be expanded on this request.'
        ]);
    }
}