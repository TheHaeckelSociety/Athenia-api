<?php
declare(strict_types=1);

namespace App\Policies\Wiki;

use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Policies\BasePolicyAbstract;

/**
 * Class IterationPolicy
 * @package App\Policies\Wiki
 */
class IterationPolicy extends BasePolicyAbstract
{
    /**
     * All logged in users can currently see all article iterations right now
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function all(User $user, Article $article)
    {
        return true;
    }
}