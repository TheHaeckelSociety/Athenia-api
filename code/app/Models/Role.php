<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\Models\HasPolicyContract;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends BaseModelAbstract implements HasPolicyContract
{
    const APP_USER = 1;
    const SUPER_ADMIN = 2;
    const ARTICLE_VIEWER = 3;
    const ARTICLE_EDITOR = 4;
    // Add more roles here start with 100 in order to avoid application collision

    /**
     * @var array[string] the roles (usefully mainly for testing I suppose)
     */
    const ROLES = [
        self::APP_USER,
        self::SUPER_ADMIN,
        self::ARTICLE_VIEWER,
        self::ARTICLE_EDITOR,
        // Add application specific roles here too
    ];

    /**
     * Has many users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Swagger definition below...
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="Role",
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
     *         property="name",
     *         type="string",
     *         maxLength=32,
     *         description="The name of the role"
     *     ),
     * )
     */
}
