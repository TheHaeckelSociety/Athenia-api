<?php
declare(strict_types=1);

namespace Tests\Unit\Gate;

use App\Gate\PrivateThreadGate;
use App\Models\User\Thread;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * Class PrivateThreadGateTest
 * @package Tests\Unit\Gate
 */
class PrivateThreadGateTest extends TestCase
{
    public function testAuthorizeSubject()
    {
        $gate = new PrivateThreadGate();

        $this->assertTrue($gate->authorizeSubject(new User()));
    }

    public function testAuthorizeThread()
    {
        $gate = new PrivateThreadGate();

        $thread =  new Thread([
            'users' => new Collection([]),
        ]);

        $user = new User();
        $user->id = 453;

        $this->assertFalse($gate->authorizeThread($user, $thread));

        $thread->users->push($user);
        $this->assertTrue($gate->authorizeThread($user, $thread));
    }
}