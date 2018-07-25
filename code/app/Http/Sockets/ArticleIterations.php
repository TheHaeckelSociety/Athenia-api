<?php

namespace App\Http\Sockets;

use App\Contracts\Repositories\Wiki\ArticleRepositoryContract;
use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Orchid\Socket\BaseSocketListener;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

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
     * ArticleIterations constructor.
     * @param ArticleRepositoryContract $articleRepository
     * @param IterationRepositoryContract $iterationRepository
     */
    public function __construct(ArticleRepositoryContract $articleRepository, IterationRepositoryContract $iterationRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->iterationRepository = $iterationRepository;
    }

    /**
     * @param ConnectionInterface $connection
     * @return array|null
     */
    public function validateArticle(ConnectionInterface $connection) : ?array
    {
        $queryString = $connection->httpRequest->getUri()->getQuery();
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
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if ($data = $this->validateArticle($conn)) {

            $data['connections']->attach($conn);
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        if ($data = $this->validateArticle($from)) {

            /** @var ConnectionInterface $client */
            foreach ($data['connections'] as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        }
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
