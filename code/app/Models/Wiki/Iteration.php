<?php
declare(strict_types=1);

namespace App\Models\Wiki;

use App\Contracts\Models\HasPolicyContract;
use App\Models\BaseModelAbstract;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Iteration
 *
 * @package App\Models\Wiki
 * @property int $id
 * @property string $content
 * @property int $created_by_id
 * @property int $article_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Wiki\Article $article
 * @property-read \App\Models\User\User $createdBy
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration query()
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Iteration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Iteration extends BaseModelAbstract implements HasPolicyContract
{
    /**
     * The article that this iteration is for
     *
     * @return BelongsTo
     */
    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Makes sure everything is by default ordered by the created at date in reverse
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        $query = parent::newQuery();

        $query->orderBy('created_at', 'desc');

        return $query;
    }

    /**
     * The user that originally created this article
     *
     * @return BelongsTo
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Swagger definition below...
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="Iteration",
     *     @SWG\Property(
     *         property="id",
     *         type="integer",
     *         format="int32",
     *         description="The primary id of the model",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of the time this was created",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of the time this was last updated",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="content",
     *         type="string",
     *         description="The content of this iteration."
     *     ),
     *     @SWG\Property(
     *         property="article_id",
     *         type="integer",
     *         format="int32",
     *         description="The primary id of the article that created this iteration is for.",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="created_by_id",
     *         type="integer",
     *         format="int32",
     *         description="The primary id of the user that created this article.",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="createdBy",
     *         description="The users that created this article.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/User")
     *     ),
     *     @SWG\Property(
     *         property="article",
     *         description="The article that this iteration is for.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Article")
     *     )
     * )
     */
}