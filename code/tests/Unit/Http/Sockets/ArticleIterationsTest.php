<?php
declare(strict_types=1);

namespace Tests\Unit\Http\Sockets;

use App\Contracts\Repositories\Wiki\ArticleRepositoryContract;
use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use App\Http\Sockets\ArticleIterations;
use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Ratchet\ConnectionInterface;
use Tests\CustomMockInterface;
use Tests\TestCase;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class ArticleIterationTest
 * @package Tests\Unit\Http\Sockets
 */
class ArticleIterationsTest extends TestCase
{
    /**
     * @var CustomMockInterface|ArticleRepositoryContract
     */
    private $articleRepository;

    /**
     * @var CustomMockInterface|IterationRepositoryContract
     */
    private $iterationRepository;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * @var ArticleIterations
     */
    private $socket;

    public function setUp()
    {
        parent::setUp();
        $this->articleRepository = mock(ArticleRepositoryContract::class);
        $this->iterationRepository = mock(IterationRepositoryContract::class);
        $this->jwtAuth = mock(JWTAuth::class);
        $this->socket = new ArticleIterations($this->articleRepository, $this->iterationRepository, $this->jwtAuth);
    }

    public function testAuthenticateUserFailsNoHeader()
    {

    }

    public function testValidateArticleFailsNoArticleID()
    {
        /** @var CustomMockInterface|RequestInterface $request */
        $request = mock(RequestInterface::class);
        /** @var CustomMockInterface|ConnectionInterface $conn */
        $conn = mock(ConnectionInterface::class);
        $conn->httpRequest = $request;

        /** @var CustomMockInterface|UriInterface $uri */
        $uri = mock(UriInterface::class);

        $uri->shouldReceive('getQuery')->once()->andReturn('something=false');

        $request->shouldReceive('getUri')->once()->andReturn($uri);

        $conn->shouldReceive('send')->once();
        $conn->shouldReceive('close')->once();

        $this->socket->validateArticle($conn);
    }

    public function testValidateArticleFailsNoArticleNotFound()
    {
        /** @var CustomMockInterface|RequestInterface $request */
        $request = mock(RequestInterface::class);
        /** @var CustomMockInterface|ConnectionInterface $conn */
        $conn = mock(ConnectionInterface::class);
        $conn->httpRequest = $request;

        /** @var CustomMockInterface|UriInterface $uri */
        $uri = mock(UriInterface::class);

        $uri->shouldReceive('getQuery')->once()->andReturn('article=1');

        $request->shouldReceive('getUri')->once()->andReturn($uri);

        $this->articleRepository->shouldReceive('findOrFail')->once()->with(1)
            ->andThrow(new ModelNotFoundException());

        $conn->shouldReceive('send')->once();
        $conn->shouldReceive('close')->once();

        $this->socket->validateArticle($conn);
    }

    public function testValidateArticleSucceeds()
    {
        /** @var CustomMockInterface|RequestInterface $request */
        $request = mock(RequestInterface::class);
        /** @var CustomMockInterface|ConnectionInterface $conn */
        $conn = mock(ConnectionInterface::class);
        $conn->httpRequest = $request;

        /** @var CustomMockInterface|UriInterface $uri */
        $uri = mock(UriInterface::class);

        $uri->shouldReceive('getQuery')->once()->andReturn('article=1');

        $request->shouldReceive('getUri')->once()->andReturn($uri);

        $article = new Article();
        $article->id = 1;

        $this->articleRepository->shouldReceive('findOrFail')->once()->with(1)
            ->andReturn($article);

        $result = $this->socket->validateArticle($conn);

        $this->assertArraySubset(['model' => $article], $result);
        $this->assertCount(0, $result['connections']);
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