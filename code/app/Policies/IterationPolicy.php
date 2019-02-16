<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\User\User;
use App\Models\Wiki\Article;

/**
 * Class IterationPolicy
 * @package App\Policies
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