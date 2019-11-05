<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\ArticleVersionCalculationServiceContract;
use App\Models\Wiki\ArticleVersion;

/**
 * Class ArticleVersionCalculationService
 * @package App\Services
 */
class ArticleVersionCalculationService implements ArticleVersionCalculationServiceContract
{
    /**
     * Figures out whether or not the new version is a major version
     *
     * @param ArticleVersion $newVersion
     * @param ArticleVersion $oldVersion
     * @return bool
     */
    public function determineIfMajor(ArticleVersion $newVersion, ArticleVersion $oldVersion): bool
    {
        return false;
    }

    /**
     * Figures out whether or not the new version is a minor version
     *
     * @param ArticleVersion $newVersion
     * @param ArticleVersion $oldVersion
     * @return bool
     */
    public function determineIfMinor(ArticleVersion $newVersion, ArticleVersion $oldVersion): bool
    {
        return false;
    }


}