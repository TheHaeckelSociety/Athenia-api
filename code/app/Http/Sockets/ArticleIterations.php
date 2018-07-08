<?php

namespace App\Http\Sockets;

use App\Contracts\Repositories\Wiki\IterationRepositoryContract;
use Orchid\Socket\BaseSocketListener;
use Ratchet\ConnectionInterface;

class ArticleIterations extends BaseSocketListener
{
    /**
     * Current clients.
     *
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * @var IterationRepositoryContract
     */
    private $iterationRepository;

    /**
     * ArticleIterations constructor.
     * @param IterationRepositoryContract $iterationRepository
     */
    public function __construct(IterationRepositoryContract $iterationRepository)
    {
        $this->iterationRepository = $iterationRepository;
        $this->clients = new \SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection!\n";
    }

    /**
     * @param ConnectionInterface $from
     * @param $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
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
