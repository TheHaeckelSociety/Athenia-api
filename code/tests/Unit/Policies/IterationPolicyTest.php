<?php
declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Policies\IterationPolicy;
use Tests\TestCase;

/**
 * Class IterationPolicyTest
 * @package Tests\Unit\Policies
 */
class IterationPolicyTest extends TestCase
{
    public function testAll()
    {
        $policy = new IterationPolicy();

        $this->assertTrue($policy->all(new User(), new Article()));
    }
}