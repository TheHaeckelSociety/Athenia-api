<?php
/**
 * Feature test for refreshing authentication tokens
 */
declare(strict_types=1);

namespace Tests\Feature\Authentication;

use Illuminate\Support\Facades\Hash;
use App\Models\User\User;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class RefreshTest
 * @package Tests\Feature\Authentication
 */
class RefreshTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    public function setUp()
    {
        parent::setUp();
        $this->mockApplicationLog();
        $this->setupDatabase();
    }

    public function testTokenRefresh()
    {
        factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => Hash::make('complex!')
        ]);

        $loginResponse = $this->json('POST', '/v1/auth/login', [
            'email' => 'test@test.com',
            'password' => 'complex!'
        ]);
        $loginResponse->assertJsonStructure([
            'token'
        ]);
        $loginResponse->assertStatus(200);

        $token = $loginResponse->original['token'];

        $response = $this->json('POST', '/v1/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertJsonStructure(['token']);

        $token = $response->original['token'];
// @todo add test for making sure the token works
//        $response = $this->json('GET', '/v4/users/me', [], [
//            'Authorization' => $token,
//        ]);
//
//        $response->assertStatus(200);
    }
}