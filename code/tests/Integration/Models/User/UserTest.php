<?php
declare(strict_types=1);

namespace Tests\Integration\Models\User;

use App\Models\User\User;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Integration\Models
 */
class UserTest extends TestCase
{
    use DatabaseSetupTrait;
    
    public function testHasRole()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->assertFalse($user->hasRole(1));

        $user = factory(User::class)->create();
        $user->roles()->attach(1);
        $this->assertFalse($user->hasRole(2));

        $user = factory(User::class)->create();
        $user->roles()->attach(1);
        $this->assertTrue($user->hasRole(1));

        $user = factory(User::class)->create();
        $user->roles()->attach(1);
        $user->roles()->attach(2);
        $this->assertTrue($user->hasRole(1));

        $user = factory(User::class)->create();
        $user->roles()->attach(1);
        $user->roles()->attach(2);

        $this->assertTrue($user->hasRole([1,6]));
        $this->assertTrue($user->hasRole([1,2]));
        $this->assertTrue($user->hasRole([1]));
        $this->assertTrue($user->hasRole([2]));
        $this->assertTrue($user->hasRole([2,3]));
        $this->assertFalse($user->hasRole([4,5]));
    }
}
