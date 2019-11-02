<?php
declare(strict_types=1);

namespace Tests\Unit\Events\Article;

use App\Events\Article\ArticleVersionCreatedEvent;
use App\Models\Wiki\ArticleVersion;
use Tests\TestCase;

/**
 * Class ArticleVersionCreatedEventTest
 * @package Tests\Unit\Events\Article
 */
class ArticleVersionCreatedEventTest extends TestCase
{
    public function testGetArticleVersion()
    {
        $model = new ArticleVersion();

        $event = new ArticleVersionCreatedEvent($model);

        $this->assertEquals($model, $event->getArticleVersion());
    }
}