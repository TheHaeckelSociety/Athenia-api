<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\Models\HasPolicyContract;
use App\Models\BaseModelAbstract;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models\User
 * @property int $id
 * @property string $email the email address of the user
 * @property string $name the full name of the user
 * @property string $password the password of the user
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wiki\Article[] $createdArticles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wiki\Iteration[] $createdIterations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Message[] $messages
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends BaseModelAbstract implements AuthenticatableContract, JWTSubject, HasPolicyContract
{
    use Authenticatable;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'password',
    ];

    /**
     * The articles that were created by this user
     *
     * @return HasMany
     */
    public function createdArticles() : HasMany
    {
        return $this->hasMany(Article::class, 'created_by_id');
    }

    /**
     * The iterations that were created by this user
     *
     * @return HasMany
     */
    public function createdIterations() : HasMany
    {
        return $this->hasMany(Iteration::class, 'created_by_id');
    }

    /**
     * The messages that were sent to a user
     *
     * @return HasMany
     */
    public function messages() : HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Swagger definition below...
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="User",
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
     *         property="email",
     *         type="string",
     *         maxLength=120,
     *         description="The email address of this user"
     *     ),
     *     @SWG\Property(
     *         property="name",
     *         type="string",
     *         maxLength=120,
     *         description="The name of this user"
     *     ),
     *     @SWG\Property(
     *         property="password",
     *         type="string",
     *         minLength=6,
     *         description="The password for this user. This cannot be read, and it can only be set."
     *     ),
     *     @SWG\Property(
     *         property="created_articles",
     *         description="The articles that this user created.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Articles")
     *     ),
     *     @SWG\Property(
     *         property="created_iterations",
     *         description="The iterations that this user created.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Iterations")
     *     )
     * )
     */
}
