<?php
declare(strict_types=1);

namespace App\Models\Wiki;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

/**
 * Class Article
 *
 * @package App\Models\Wiki
 * @property int $id
 * @property int $created_by_id
 * @property string $title
 * @property-read null|string $content
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User\User $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wiki\Iteration[] $iterations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Wiki\Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends BaseModelAbstract implements HasPolicyContract, HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * Values that are appending on a toArray function call
     *
     * @var array
     */
    protected $appends = [
        'content',
    ];

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
     * All of the iterations
     *
     * @return HasMany
     */
    public function iterations() : HasMany
    {
        return $this->hasMany(Iteration::class)->orderByDesc('created_at');
    }

    /**
     * Gets the content of the article
     *
     * @return null|string
     */
    public function getContentAttribute() : ?string
    {
        /** @var Iteration|null $iteration */
        $iteration = $this->iterations->first();
        return $iteration ? $iteration->content : null;
    }

    /**
     * Build the model validation rules
     * @param array $params
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        return [
            static::VALIDATION_RULES_BASE => [
                'title' => [
                    'string',
                    'max:120',
                ],
                'created_by_id' => [
                    'integer',
                    Rule::exists('users', 'id'),
                ],
            ],
            static::VALIDATION_RULES_CREATE => [
                static::VALIDATION_PREPEND_REQUIRED => [
                    'title',
                    'created_by_id',
                ],
            ],
        ];
    }

    /**
     * Swagger definition below...
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="Article",
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
     *         property="title",
     *         type="string",
     *         maxLength=120,
     *         description="The title of this article"
     *     ),
     *     @SWG\Property(
     *         property="content",
     *         type="string",
     *         readonly=true,
     *         description="The content of this article. Note that this can not be changed directly through the article. It should be saved by adding a new iteration to the article"
     *     ),
     *     @SWG\Property(
     *         property="created_by_id",
     *         type="integer",
     *         format="int32",
     *         description="The primary id of the user that created this article",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="createdBy",
     *         description="The users that created this article.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/User")
     *     ),
     *     @SWG\Property(
     *         property="iterations",
     *         description="The iterations for this article.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Iterations")
     *     )
     * )
     */
}