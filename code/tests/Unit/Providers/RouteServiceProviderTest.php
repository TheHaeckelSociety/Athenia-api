<?php
/**
 * Unit test for the route service provider
 */
declare(strict_types=1);

namespace Tests\Unit\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use App\Providers\RouteServiceProvider;
use Tests\TestCase;

/**
 * Class RouteServiceProviderTest
 * @package Tests\Unit\Providers
 */
class RouteServiceProviderTest extends TestCase
{
    public function testMap()
    {
        $middleware = mock(RouteRegistrar::class);

        $middleware->shouldAllowMockingMethod('namespace');
        $middleware->shouldAllowMockingMethod('group');

        $middleware->shouldReceive('namespace')->once()->with('App\Http\V1\Controllers')->andReturn($middleware);
        $middleware->shouldReceive('group')->once()->with(\Mockery::on(function(string $path) {
            if (!strpos($path, 'routes/api-v1.php')) $this->fail('route path not set properly');

            return true;
        }));

        $router = mock(Router::class);
        $router->shouldAllowMockingMethod('middleware');
        $router->shouldReceive('middleware')->once()->with('api-v1')->andReturn($middleware);

        $app = mock(Application::class);

        $app->shouldReceive('make')->once()->with(Router::class)->andReturn($router);

        $provider = new RouteServiceProvider($app);
        $provider->map();
    }
}