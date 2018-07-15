<?php
declare(strict_types=1);

namespace Tests\Unit\Http\Sockets;

use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use App\Http\Sockets\ArticleIterations;
use Ratchet\ConnectionInterface;
use Tests\CustomMockInterface;
use Tests\TestCase;

/**
 * Class ArticleIterationTest
 * @package Tests\Unit\Http\Sockets
 */
class ArticleIterationsTest extends TestCase
{
    /**
     * @var CustomMockInterface|IterationRepositoryContract
     */
    private $repository;

    /**
     * @var ArticleIterations
     */
    private $socket;

    public function setUp()
    {
        parent::setUp();
        $this->repository = mock(IterationRepositoryContract::class);
        $this->socket = new ArticleIterations($this->repository);
    }

    public function testOnOpen()
    {
        $conn = mock(ConnectionInterface::class);

        $this->socket->onOpen($conn);
    }

    public function testOnClose()
    {
        $conn = mock(ConnectionInterface::class);

        $this->socket->onClose($conn);
    }

    public function testOnError()
    {
        $conn = mock(ConnectionInterface::class);

        $conn->shouldReceive('close')->once();

        $this->socket->onError($conn, new \Exception());
    }
}