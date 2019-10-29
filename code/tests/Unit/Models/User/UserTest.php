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
    public function testAssets()
    {
        $user = new User();
        $relation = $user->assets();

        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('assets.user_id', $relation->getQualifiedForeignKeyName());
    }

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

        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('messages.to_id', $relation->getQualifiedForeignKeyName());
    }

    public function testPaymentMethods()
    {
        $user = new User();
        $relation = $user->paymentMethods();

        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('payment_methods.owner_id', $relation->getQualifiedForeignKeyName());
    }

    public function testResource()
    {
        $user = new User();
        $relation = $user->resource();

        $this->assertEquals('resources.resource_id', $relation->getQualifiedForeignKeyName());
        $this->assertEquals('resources.resource_type', $relation->getQualifiedMorphType());
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
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

    public function testThreads()
    {
        $model = new User();
        $relation = $model->threads();

        $this->assertEquals('thread_user', $relation->getTable());
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('thread_user.user_id', $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals('thread_user.thread_id', $relation->getQualifiedRelatedPivotKeyName());
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