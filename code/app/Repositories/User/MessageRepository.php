<?php
declare(strict_types=1);

namespace App\Repositories\User;

use Psr\Log\LoggerInterface as LogContract;
use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Models\User\Message;
use App\Repositories\BaseRepositoryAbstract;
use App\Repositories\Traits\NotImplemented as NotImplemented;

/**
 * Class MessageRepository
 * @package App\Repositories\User
 */
class MessageRepository extends BaseRepositoryAbstract implements MessageRepositoryContract
{
    use NotImplemented\FindAll, NotImplemented\Delete, NotImplemented\FindOrFail;

    /**
     * MessageRepository constructor.
     * @param Message $model
     * @param LogContract $log
     */
    public function __construct(Message $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }

    /**
     * @SWG\Definition(
     *     definition="Messages",
     *     @SWG\Property(
     *         property="data",
     *         description="A list of message models",
     *         type="array",
     *         minItems=0,
     *         maxItems=100,
     *         uniqueItems=true,
     *         @SWG\Items(ref="#/definitions/Message")
     *     )
     * )
     */
}