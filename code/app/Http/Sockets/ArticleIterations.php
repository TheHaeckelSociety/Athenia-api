<?php
declare(strict_types=1);

namespace App\Http\Sockets;

use App\Contracts\Repositories\Wiki\ArticleRepositoryContract;
use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use App\Exceptions\AuthenticationException;
use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use Orchid\Socket\BaseSocketListener;
use Psr\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class ArticleIterations
 * @package App\Http\Sockets
 */
class ArticleIterations extends BaseSocketListener
{
    /**
     * A list of articles that have already been loaded
     *
     * @var array[]
     */
    protected $loadedArticles = [];

    /**
     * @var ArticleRepositoryContract
     */
    private $articleRepository;

    /**
     * @var IterationRepositoryContract
     */
    private $iterationRepository;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * ArticleIterations constructor.
     * @param ArticleRepositoryContract $articleRepository
     * @param IterationRepositoryContract $iterationRepository
     * @param JWTAuth $jwtAuth
     */
    public function __construct(ArticleRepositoryContract $articleRepository,
                                IterationRepositoryContract $iterationRepository, JWTAuth $jwtAuth)
    {
        $this->articleRepository = $articleRepository;
        $this->iterationRepository = $iterationRepository;
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * Parses the authentication header from a request
     *
     * @param RequestInterface $httpRequest
     * @return string
     */
    public function parseAuthHeader(RequestInterface $httpRequest) : string
    {
        if (!$httpRequest->hasHeader('Authorization')) {
            throw new AuthenticationException('This routes requires that the user is logged in.');
        }

        $authHeader = $httpRequest->getHeader('Authorization');

        if (!count($authHeader)) {
            throw new AuthenticationException('Invalid auth header format.');
        }

        $header = $authHeader[0];
        $headerParts = explode(' ', $header);

        if (count($headerParts) <= 1) {
            throw new AuthenticationException('Invalid auth header format.');
        }

        return $headerParts[1];
    }

    /**
     * Authenticates that the user is logged in properly
     *
     * @param ConnectionInterface $connection
     * @return User|null
     */
    public function authenticateUser(ConnectionInterface $connection) : ?User
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $connection->httpRequest;

        $authHeader = $this->parseAuthHeader($httpRequest);

        $this->jwtAuth->setToken($authHeader);

        /** @var User $user */
        if ($user = $this->jwtAuth->authenticate()) {
            return $user;
        }

        throw new AuthenticationException('Unable to authenticate user. Please try logging in again.');
    }

    /**
     * @param ConnectionInterface $connection
     * @return array
     * @throws \Exception
     */
    public function validateArticle(ConnectionInterface $connection) : array
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $connection->httpRequest;

        $queryString = $httpRequest->getUri()->getQuery();
        preg_match_all("/([^,= ]+)=([^,= ]+)/", $queryString, $result);
        $query = array_combine($result[1], $result[2]);

        if (!isset($query['article'])) {
            throw new \Exception('Unknown error, please try again later');
        }

        $articleId = (int)$query['article'];

        if (!isset ($this->loadedArticles[$articleId])) {
            $article = $this->articleRepository->findOrFail($query['article']);
            $this->loadedArticles[$articleId] = [
                'model' => $article,
                'connections' => new SplObjectStorage(),
            ];
        }

        return $this->loadedArticles[$articleId];
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        try {
            $user = $this->authenticateUser($conn);
            $data = $this->validateArticle($conn);

            $data['connections']->attach($conn, $user);

        } catch (\Exception $e) {

            $conn->send($e->getMessage());
            $conn->close();
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $data = $this->validateArticle($from);

            /** @var SplObjectStorage $connections */
            $connections = $data['connections'];
            /** @var Article $article */
            $article = $data['model'];

            $user = $connections->offsetGet($from);

            $msgData = json_decode($msg, true);

            if ($updatedModel = $this->runAction($user, $article, $msgData)) {

                $data['model'] = $updatedModel;

                /** @var ConnectionInterface $client */
                foreach ($data['connections'] as $client) {
                    if ($from !== $client) {
                        $client->send($updatedModel->content);
                    }
                }

                $this->loadedArticles[$updatedModel->id] = $data;
            }
        } catch (\Exception $e) {

            $from->send($e->getMessage());
            $from->close();
        }
    }

    /**
     * Runs the correct action
     *
     * @param User $user
     * @param Article $article
     * @param array $msg
     * @return Article|null
     */
    public function runAction(User $user, Article $article, array $msg) : ?Article
    {
        if (isset($msg['action'])) {
            switch ($msg['action']) {
                case 'remove':
                    return $this->handleRemoveAction($user, $article, $msg);

            }
        }

        return null;
    }

    /**
     * Handles the remove action properly
     *
     * @param User $user
     * @param Article $article
     * @param $msg
     * @return Article|null
     */
    public function handleRemoveAction(User $user, Article $article, $msg) : ?Article
    {
        $startPosition = $msg['start_position'] ?? null;
        $length = $msg['length'] ?? null;

        if ($startPosition && $length) {
            /** @var Iteration $iteration */
            $this->iterationRepository->create([
                'content' => substr_replace($article->content, '', $startPosition, $length),
                'created_by_id' => $user->id,
            ], $article);

            return $article->refresh();
        }

        return null;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        try {
            $data = $this->validateArticle($conn);
            $data['connections']->detach($conn);
        } catch (\Exception $e) {
            $conn->send($e->getMessage());
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
