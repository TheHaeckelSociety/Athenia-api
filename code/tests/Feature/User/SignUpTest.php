<?php
/**
 * User Sign Up API Test
 */
declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class UserSignUpTest
 * @package Tests\Feature\User
 */
class SignUpTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    public function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();
    }

    public function testWebsiteSuccess()
    {
        $properties = [
            'email' => 'test@test.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/v1/sign-up', $properties);

        $response->assertStatus(201);
        $model = $response->original;

        //Make sure the password was hashed properly separately
        $password = $properties['password'];
        unset($properties['password']);

        $this->assertEquals($properties, [
            'email' => $model->email,
        ]);

        $this->assertTrue(Hash::check($password, $model->password));
    }

    public function testWebsiteSignUpFailureMissingRequiredFields()
    {
        $response = $this->json('POST', '/v1/sign-up', []);

        $response->assertStatus(400);
        $response->assertJson([
            'errors' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ]);
    }

    public function testWebsiteSignUpFailsInvalidStringFields()
    {
        $response = $this->json('POST', '/v1/sign-up', [
            'email' => 1,
            'password' => 1,
        ]);

        $response->assertJson(['errors' => [
            'email' => ['The email must be a string.'],
            'password' => ['The password must be a string.'],
        ]]);

        $response->assertStatus(400);
    }

    public function testWebsiteSignUpFailsTooShortFields()
    {
        $response = $this->json('POST', '/v1/sign-up', [
            'password' => 'a',
        ]);
        $response->assertJson(['errors' => [
            'password' => ['The password must be at least 6 characters.'],
        ]]);

        $response->assertStatus(400);
    }

    public function testWebsiteSignUpFailsTooLongFields()
    {
        $response = $this->json('POST', '/v1/sign-up', [
            'email' => str_repeat('a', 257),
            'password' => str_repeat('a', 257),
        ]);
        $response->assertJson(['errors' => [
            'email' => ['The email may not be greater than 256 characters.'],
            'password' => ['The password may not be greater than 256 characters.'],
        ]]);

        $response->assertStatus(400);
    }

    public function testWebsiteSignUpFailsInvalidEmailFields()
    {
        $response = $this->json('POST', '/v1/sign-up', [
            'email' => 'asdf'
        ]);
        $response->assertJson(['errors' => [
            'email' => ['The email must be a valid email address.'],
        ]]);

        $response->assertStatus(400);
    }

    public function testWebsiteSignUpFailsEmailInUse()
    {
        factory(User::class)->create(['email' => 'test@test.com']);

        $response = $this->json('POST', '/v1/sign-up', [
            'email' => 'test@test.com'
        ]);
        $response->assertJson(['errors' => [
            'email' => ['The email has already been taken.'],
        ]]);

        $response->assertStatus(400);
    }
}
