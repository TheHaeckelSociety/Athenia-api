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
    public function testMessages()
    {
        $user = new User();
        $relation = $user->messages();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('messages.user_id', $relation->getQualifiedForeignKeyName());
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