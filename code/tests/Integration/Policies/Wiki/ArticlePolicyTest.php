<?php
declare(strict_types=1);

namespace Tests\Integration\Policies\Wiki;

use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Policies\Wiki\ArticlePolicy;
use Tests\TestCase;

/**
 * Class ArticlePolicyTest
 * @package Tests\Integration\Policies\Wiki
 */
class ArticlePolicyTest extends TestCase
{
    public function testAll()
    {
        $policy = new ArticlePolicy();

        $this->assertTrue($policy->all(new User()));
    }

    public function testView()
    {
        $policy = new ArticlePolicy();

        $this->assertTrue($policy->view(new User(), new Article()));
    }

    public function testCreate()
    {
        $policy = new ArticlePolicy();

        $this->assertTrue($policy->create(new User()));
    }

    public function testUpdate()
    {
        $policy = new ArticlePolicy();

        $user = new User();
        $user->id = 453;

        $article = new Article([
            'created_by_id' => $user->id,
        ]);

        $this->assertTrue($policy->update($user, $article));

        $user->id = 3452;
        $this->assertFalse($policy->update($user, $article));
    }
}