<?php
/**
 * Unit test for the auth service provider
 */
declare(strict_types=1);

namespace Tests\Unit\Providers;

use App\Contracts\Repositories\User\UserRepositoryContract;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use App\Policies\BasePolicyAbstract;
use App\Providers\AuthServiceProvider;
use App\Services\UserAuthenticationService;
use ReflectionClass;
use Tests\TestCase;
use Tests\Traits\ReflectionHelpers;

/**
 * Class AuthServiceProviderTest
 * @package Tests\Unit\Providers
 */
class AuthServiceProviderTest extends TestCase
{
    use ReflectionHelpers;

    public function getRegisteredPolicies()
    {
        $gate = $this->app->make(Gate::class);
        $reflection = new ReflectionClass($gate);
        $property = $reflection->getProperty('policies');
        $property->setAccessible(true);

        return $property->getValue($gate);
    }

    public function testAllNonAbstractPoliciesAreRegistered()
    {
        $foundPolicies = $this->getObjectsInNamespace('App\Policies');

        $provider = new AuthServiceProvider($this->app);
        $provider->registerPolicies();

        $registeredPolicies = array_values($this->getRegisteredPolicies());

        $nonAbstractPolicies = [];
        foreach ($foundPolicies as $policy) {
            $reflection = new ReflectionClass($policy);

            if ($reflection->isSubclassOf(BasePolicyAbstract::class) && !$reflection->isAbstract()) {
                $nonAbstractPolicies[] = $policy;
            }
        }

        $unregisteredPolicies = array_diff($nonAbstractPolicies, $registeredPolicies);

        if (count($unregisteredPolicies)) {
            $this->fail('Not all policies have been registered. Make sure the following policies are registered - ' . implode($unregisteredPolicies));
        }
    }

    public function testUserAuthenticationRegistered()
    {
        $app = mock(Application::class);

        $app->shouldReceive('make')->once()->with(UserRepositoryContract::class)->andReturn(mock(UserRepositoryContract::class));
        $app->shouldReceive('make')->once()->with(Hasher::class)->andReturn(mock(Hasher::class));

        $auth = mock(AuthManager::class);

        $auth->shouldReceive('provider')->once()->with('user-authentication', \Mockery::on(function($callback) use ($app) {
            $result = $callback($app, []);

            $this->assertInstanceOf(UserAuthenticationService::class, $result);

            return true;
        }));

        $app->shouldReceive('make')->once()->with('auth')->andReturn($auth);

        $provider = new AuthServiceProvider($app);

        $provider->boot();
    }
}