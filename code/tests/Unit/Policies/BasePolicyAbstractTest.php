<?php
declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User\User;
use App\Policies\BasePolicyAbstract;
use Tests\TestCase;

/**
 * Class BasePolicyAbstractTest
 * @package Tests\Unit\Policies
 */
class BasePolicyAbstractTest extends TestCase
{
    public function testBefore()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertNull($policy->before(new User()));
    }
}