<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Wiki;

use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * Class ArticleTest
 * @package Tests\Unit\Models\Wiki
 */
class ArticleTest extends TestCase
{
    public function testCreatedBy()
    {
        $article = new Article();
        $relation = $article->createdBy();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('articles.created_by_id', $relation->getQualifiedForeignKeyName());
    }

    public function testIterations()
    {
        $article = new Article();
        $relation = $article->iterations();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('articles.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('iterations.article_id', $relation->getQualifiedForeignKeyName());

        $this->assertContains('order by', $relation->toSql());
        $this->assertContains('created_at', $relation->toSql());
        $this->assertContains('desc', $relation->toSql());
    }
}