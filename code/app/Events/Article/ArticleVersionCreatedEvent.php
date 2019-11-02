<?php
declare(strict_types=1);

namespace App\Events\Article;

use App\Models\Wiki\ArticleVersion;

/**
 * Class ArticleVersionCreatedEvent
 * @package App\Events\Article
 */
class ArticleVersionCreatedEvent
{
    /**
     * @var ArticleVersion
     */
    private $articleVersion;

    /**
     * ArticleVersionCreatedEvent constructor.
     * @param ArticleVersion $articleVersion
     */
    public function __construct(ArticleVersion $articleVersion)
    {
        $this->articleVersion = $articleVersion;
    }

    /**
     * @return ArticleVersion
     */
    public function getArticleVersion(): ArticleVersion
    {
        return $this->articleVersion;
    }
}