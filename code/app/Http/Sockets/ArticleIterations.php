<?php

namespace App\Http\Sockets;

use App\Contracts\Repositories\Wiki\ArticleRepositoryContract;
use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use App\Models\User\User;
use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @var Article[]
     */
    protected $loadedArticles;

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
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if ($this->authenticateUser($conn) && $data = $this->validateArticle($conn)) {

            $data['connections']->attach($conn);
        }
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

        if (!$httpRequest->hasHeader('Authorization')) {
            $connection->send('This routes requires that the user is logged in.');
            $connection->close();
        }

        $authHeader = $httpRequest->getHeader('Authorization');

        if (!count($authHeader)) {
            $connection->send('Invalid auth header format.');
            $connection->close();
        }

        $header = $authHeader[0];
        $headerParts = explode(' ', $header);

        if (!count($headerParts) > 1) {
            $connection->send('Invalid auth header format.');
            $connection->close();
        } else {

            $this->jwtAuth->setToken($headerParts[1]);

            /** @var User $user */
            if ($user = $this->jwtAuth->authenticate()) {
                return $user;
            }

            $connection->send('Unable to authenticate user. Please try logging in again.');
            $connection->close();
        }

        return null;
    }

    /**
     * @param ConnectionInterface $connection
     * @return array|null
     */
    public function validateArticle(ConnectionInterface $connection) : ?array
    {
        /** @var RequestInterface $httpRequest */
        $httpRequest = $connection->httpRequest;

        $queryString = $httpRequest->getUri()->getQuery();
        preg_match_all("/([^,= ]+)=([^,= ]+)/", $queryString, $result);
        $query = array_combine($result[1], $result[2]);

        if (!isset($query['article'])) {
            $connection->send('Unknown error, please try again later');
            $connection->close();
        } else {
            try {
                if (!isset ($this->loadedArticles[$query['article']])) {
                    $article = $this->articleRepository->findOrFail($query['article']);
                    $this->loadedArticles[$query['article']] = [
                        'model' => $article,
                        'connections' => new SplObjectStorage(),
                    ];
                }

                return $this->loadedArticles[$query['article']];

            } catch (ModelNotFoundException $e) {
                $connection->send('Article not found, please double check the article id');
                $connection->close();
            }
        }

        return null;
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $user = $this->authenticateUser($from);
        $data = $this->validateArticle($from);
        if ($user && $data) {

            $msgData = json_decode($msg, true);

            if ($updatedModel = $this->runAction($user, $data['model'], $msgData)) {

                $data['model'] = $updatedModel;

                /** @var ConnectionInterface $client */
                foreach ($data['connections'] as $client) {
                    if ($from !== $client) {
                        $client->send($updatedModel->content);
                    }
                }
            }
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
            $this->iterationRepository->create([
                'content' => substr_replace($article->content, '', $startPosition, $length),
                'created_by_id' => $user->id,
            ], $article);
        }

        return null;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        if ($data = $this->validateArticle($conn)) {

            $data['connections']->detach($conn);
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
