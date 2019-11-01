<?php
declare(strict_types=1);

namespace Tests\Unit\Gate;

use App\Gate\GeneralThreadGate;
use App\Models\User\Thread;
use App\Models\User\User;
use Tests\TestCase;

/**
 * Class GeneralThreadGateTest
 * @package Tests\Unit\Gate
 */
class GeneralThreadGateTest extends TestCase
{
    public function testAuthorizeSubject()
    {
        $gate = new GeneralThreadGate();

        $this->assertTrue($gate->authorizeSubject(new User()));
    }

    public function testAuthorizeThread()
    {
        $gate = new GeneralThreadGate();

        $this->assertTrue($gate->authorizeThread(new User(), new Thread()));
    }
}