<?php
/**
 * Feature test for the status controller
 */
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

class StatusTest extends TestCase
{
    use MocksApplicationLog;

    public function setUp()
    {
        parent::setUp();
        $this->mockApplicationLog();
    }

    public function testSuccess()
    {
        $response = $this->get('/v1/status');

        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'ok',
        ]);
    }
}