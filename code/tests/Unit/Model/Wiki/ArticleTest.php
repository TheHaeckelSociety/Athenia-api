<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Wiki;

use App\Models\Wiki\Article;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        $this->assertEquals('articles.created_by_id', $relation->getQualifiedForeignKey());
    }
}