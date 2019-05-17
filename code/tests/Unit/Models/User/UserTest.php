<?php
declare(strict_types=1);

namespace Tests\Unit\Models\User;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User\User;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit\Models\User
 */
class UserTest extends TestCase
{
    public function testBallotCompletions()
    {
        $user = new User();
        $relation = $user->ballotCompletions();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('ballot_completions.user_id', $relation->getQualifiedForeignKeyName());
    }

    public function testCreatedArticles()
    {
        $user = new User();
        $relation = $user->createdArticles();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('articles.created_by_id', $relation->getQualifiedForeignKeyName());
    }

    public function testCreatedIterations()
    {
        $user = new User();
        $relation = $user->createdIterations();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('iterations.created_by_id', $relation->getQualifiedForeignKeyName());
    }

    public function testMessages()
    {
        $user = new User();
        $relation = $user->messages();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('messages.user_id', $relation->getQualifiedForeignKeyName());
    }

    public function testPaymentMethods()
    {
        $user = new User();
        $relation = $user->paymentMethods();

        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('payment_methods.owner_id', $relation->getQualifiedForeignKeyName());
    }

    public function testRoles()
    {
        $role = new User();
        $relation = $role->roles();

        $this->assertEquals('role_user', $relation->getTable());
        $this->assertEquals('role_user.user_id', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('role_user.role_id', $relation->getQualifiedRelatedPivotKeyName());
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
    }

    public function testSubscriptions()
    {
        $user = new User();
        $relation = $user->subscriptions();

        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('subscriptions.subscriber_id', $relation->getQualifiedForeignKeyName());
    }

    public function testGetJWTIdentifier()
    {
        $user = new User();
        $user->id = 4352;

        $this->assertEquals(4352, $user->getJWTIdentifier());
    }

    public function testGetJWTCustomClaims()
    {
        $user = new User();

        $this->assertEquals([], $user->getJWTCustomClaims());
    }
}