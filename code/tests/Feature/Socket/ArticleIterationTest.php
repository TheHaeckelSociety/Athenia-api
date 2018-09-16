<?php
declare(strict_types=1);

namespace Tests\Feature\Socket;

use App\Http\Sockets\ArticleIterations;
use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use App\Repositories\Wiki\ArticleRepository;
use App\Repositories\Wiki\IterationRepository;
use Carbon\Carbon;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Ratchet\ConnectionInterface;
use Tests\CustomMockInterface;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class ArticleIterationTest
 * @package Tests\Feature\Socket
 */
class ArticleIterationTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var ArticleIterations
     */
    private $socket;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * Array of open connections with an article acting as a key
     *
     * @var CustomMockInterface[]|ConnectionInterface[]
     */
    private $connections;

    public function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
        $this->jwtAuth = $this->app->make(JWTAuth::class);
        $this->socket = new ArticleIterations(
            new ArticleRepository(new Article(), $this->getGenericLogMock()),
            new IterationRepository(new Iteration(), $this->getGenericLogMock()),
            $this->jwtAuth
        );
        $this->connections = [];
    }

    /**
     * Create a mock connection
     *
     * @param Article $article
     * @return ConnectionInterface|CustomMockInterface
     */
    public function createValidConnection(Article $article)
    {
        if (!isset($this->connections[$article->id])) {
            $this->connections[$article->id] = [];
        }

        /** @var User $user */
        $user = factory(User::class)->create();

        $httpRequest = mock(RequestInterface::class);

        $httpRequest->shouldReceive('hasHeader')->once()->with('Authorization')->andReturn(true);
        $httpRequest->shouldReceive('getHeader')->once()->with('Authorization')->andReturn(
            ['Bearer ' . $this->jwtAuth->fromSubject($user)]
        );

        /** @var CustomMockInterface|UriInterface $uri */
        $uri = mock(UriInterface::class);

        $uri->shouldReceive('getQuery')->andReturn('article=' . $article->id);

        $httpRequest->shouldReceive('getUri')->andReturn($uri);

        /** @var CustomMockInterface|ConnectionInterface $conn */
        $conn = mock(ConnectionInterface::class);
        $conn->httpRequest = $httpRequest;
        $this->socket->onOpen($conn);

        $this->connections[$article->id][] = $conn;
        return $conn;
    }

    public function testOnOpenClosesOnError()
    {
        $connection = mock(ConnectionInterface::class);
        $connection->shouldReceive('close');
        $connection->shouldReceive('send');

        $this->socket->onOpen($connection);
    }

    public function testOnOpen()
    {
        $article = factory(Article::class)->create();

        $this->createValidConnection($article);
    }

    public function testOnMessageClosesOnError()
    {
        $connection = mock(ConnectionInterface::class);
        $connection->shouldReceive('close');
        $connection->shouldReceive('send');

        $this->socket->onMessage($connection, '');
    }

    public function testOnRemoveMessage()
    {
        /**
         * This should not receive any messages, since we are not going to be updating this article
         */
        $this->createValidConnection(factory(Article::class)->create());

        $article = factory(Article::class)->create();
        /**
         * Create some old iterations that should not affect the output
         */
        factory(Iteration::class, 5)->create([
            'article_id' => $article->id,
        ]);
        factory(Iteration::class)->create([
            'article_id' => $article->id,
            'content' => 'This is a removal test of iugwhw something.',
        ]);
        $listeningConnection = $this->createValidConnection($article);
        $fromConnection = $this->createValidConnection($article);

        $listeningConnection->shouldReceive('send')->once()->with('This is a removal test of something.');

        $message = json_encode([
            'action' => 'remove',
            'start_position' => 26,
            'length' => 7,
        ]);
        $this->socket->onMessage($fromConnection, $message);
    }
}