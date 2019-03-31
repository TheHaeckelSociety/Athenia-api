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

    /**
     * @var StripeCustomerServiceContract|CustomMockInterface
     */
    private $stripeCustomerService;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();

        $this->stripeCustomerService = mock(StripeCustomerServiceContract::class);

        $this->app->bind(StripeCustomerServiceContract::class, function() {
            return $this->stripeCustomerService;
        });
    }

    public function testGetMeSuccess()
    {
        $myCurrentUser = factory(User::class)->create();

        $this->actingAs($myCurrentUser);

        $this->stripeCustomerService->shouldReceive('createCustomer')->once()->andThrow(new Exception());

        $response = $this->json('GET', '/v1/users/me');
        $response->assertExactJson($myCurrentUser->toArray());
        $response->assertStatus(200);
    }

    public function testGetMeSetsCustomerKeyIfNotSet()
    {
        $myCurrentUser = factory(User::class)->create();

        $this->actingAs($myCurrentUser);

        $this->stripeCustomerService->shouldReceive('createCustomer')->once()
            ->with(Mockery::on(function (User $user) {
                $user->stripe_customer_key = 'test_key';
                return true;
            }));

        $response = $this->json('GET', '/v1/users/me');

        $myCurrentUser->stripe_customer_key = 'test_key';
        $response->assertExactJson($myCurrentUser->toArray());
        $response->assertStatus(200);
    }

    public function testGetMeSuccessWhenCustomerKeyIsSet()
    {
        $myCurrentUser = factory(User::class)->create([
            'stripe_customer_key' => 'test_key',
        ]);

        $this->actingAs($myCurrentUser);

        $response = $this->json('GET', '/v1/users/me');

        $response->assertExactJson($myCurrentUser->toArray());
        $response->assertStatus(200);
    }
}