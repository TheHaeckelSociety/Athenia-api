<?php
/**
 * Abstract test case
 */
declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Overriding this - because i am handling my own database stuff
     *
     * Boot the testing helper traits.
     *
     * @return void
     */
    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[WithoutMiddleware::class])) {
            $this->disableMiddlewareForAllTests();
        }

        if (isset($uses[WithoutEvents::class])) {
            $this->disableEventsForAllTests();
        }
    }

    /**
     * This is used just temporarily often to enable debugging on the integration tests.
     */
    protected function enableDebug()
    {
        config(['app.debug' => true]);
    }

    /**
     * Get a logging instance that ignores anything going on
     *
     * @return LogContract
     */
    protected function getGenericLogMock()
    {
        $logMock = mock(LogContract::class);
        $logMock->shouldReceive('info');
        return $logMock;
    }
}
