<?php
declare(strict_types=1);

namespace Tests\Feature\Http\User\PaymentMethod;

use App\Models\User\User;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class UserPaymentMethodCreateTest
 * @package Tests\Feature\Http\User\PaymentMethod
 */
class UserPaymentMethodCreateTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var string
     */
    private $path = '/v1/users/';

    /**
     * @var User
     */
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
        $this->mockApplicationLog();

        $this->user = factory(User::class)->create();

        $this->path.= $this->user->id . '/payment-methods';
    }

    public function testNotLoggedInUserBlocked()
    {
        $response = $this->json('POST', $this->path);

        $response->assertStatus(403);
    }

    public function testIncorrectUserRoleBlocked()
    {
        $this->actAsUser();
        $response = $this->json('POST', $this->path);

        $response->assertStatus(403);
    }

    public function testCreateSuccessful()
    {
        $this->actingAs($this->user);

        $response = $this->json('POST', $this->path, [
            'payment_method_key' => 'test_key',
            'payment_method_type' => 'test_type',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'payment_method_key' => 'test_key',
            'payment_method_type' => 'test_type',
        ]);
    }

    public function testCreateFailsRequiredFieldsNotPresent()
    {
        $this->actingAs($this->user);

        $response = $this->json('POST', $this->path);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'payment_method_key' => ['The payment method key field is required.'],
                'payment_method_type' => ['The payment method type field is required.'],
            ]
        ]);
    }

    public function testCreateFailsInvalidStringFields()
    {
        $this->actingAs($this->user);

        $response = $this->json('POST', $this->path, [
            'payment_method_key' => 1,
            'payment_method_type' => 1,
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'payment_method_key' => ['The payment method key must be a string.'],
                'payment_method_type' => ['The payment method type must be a string.'],
            ]
        ]);
    }

    public function testCreateFailsStringsTooLong()
    {
        $this->actingAs($this->user);

        $response = $this->json('POST', $this->path, [
            'payment_method_key' => str_repeat('a', 121),
            'payment_method_type' => str_repeat('a', 21),
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'errors' => [
                'payment_method_key' => ['The payment method key may not be greater than 120 characters.'],
                'payment_method_type' => ['The payment method type may not be greater than 20 characters.'],
            ]
        ]);
    }
}