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

    public function testAll()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertFalse($policy->all(new User()));
    }

    public function testView()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertFalse($policy->view(new User(), new User()));
    }

    public function testCreate()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertFalse($policy->create(new User()));
    }

    public function testUpdate()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertFalse($policy->update(new User(), new User()));
    }

    public function testDelete()
    {
        /** @var BasePolicyAbstract $policy */
        $policy = $this->getMockForAbstractClass(BasePolicyAbstract::class);

        $this->assertFalse($policy->delete(new User(), new User()));
    }
}