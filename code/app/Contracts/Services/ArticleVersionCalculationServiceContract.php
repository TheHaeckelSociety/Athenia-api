<?php
declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Wiki\ArticleVersion;

/**
 * Interface ArticleVersionCalculationServiceContract
 * @package App\Contracts\Services
 */
interface ArticleVersionCalculationServiceContract
{
    /**
     * Figures out whether or not the new version is a major version
     *
     * @param ArticleVersion $newVersion
     * @param ArticleVersion $oldVersion
     * @return bool
     */
    public function determineIfMajor(ArticleVersion $newVersion, ArticleVersion $oldVersion): bool;

    /**
     * Figures out whether or not the new version is a minor version
     *
     * @param ArticleVersion $newVersion
     * @param ArticleVersion $oldVersion
     * @return bool
     */
    public function determineIfMinor(ArticleVersion $newVersion, ArticleVersion $oldVersion): bool;
}