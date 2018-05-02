<?php
/**
 * Feature test for the logout functionality
 */
declare(strict_types=1);

namespace Tests\Feature\Authentication;

use App\Http\Middleware\LogMiddleware;
use App\Models\User\User;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class LogoutTest
 * @package Tests\Feature\Authentication
 */
class LogoutTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    public function setUp()
    {
        parent::setUp();
        $this->mockApplicationLog();
        $this->setupDatabase();
    }

    public function testLogout()
    {
        $this->expectException(TokenBlacklistedException::class);

        $this->app['env'] = 'testing-override';  // @todo fix this
        $this->app->instance(LogMiddleware::class, new class {
            public function handle($request, $next) {
                return $next($request);
            }
        });

        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->json('POST', '/v1/auth/logout', [], ['Authorization' => 'Bearer ' . $token]);
        $this->app['env'] = 'testing'; // @todo resolve
        $response->assertStatus(200);
        JWTAuth::authenticate($token);
    }
}