<?php
declare(strict_types=1);

namespace App\Repositories\Wiki;

use App\Contracts\Repositories\Wiki\ArticleVersionRepositoryContract;
use App\Models\Wiki\ArticleVersion;
use App\Repositories\BaseRepositoryAbstract;
use Psr\Log\LoggerInterface as LogContract;

/**
 * Class ArticleVersionRepository
 * @package App\Repositories\Wiki
 */
class ArticleVersionRepository extends BaseRepositoryAbstract implements ArticleVersionRepositoryContract
{
    /**
     * ArticleVersionRepository constructor.
     * @param ArticleVersion $model
     * @param LogContract $log
     */
    public function __construct(ArticleVersion $model, LogContract $log)
    {
        parent::__construct($model, $log);
    }
}