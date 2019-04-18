<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\Payment\PaymentMethod;
use App\Models\Role;
use App\Models\Subscription\Subscription;
use App\Models\Traits\HasValidationRules;
use App\Models\Wiki\Article;
use App\Models\Wiki\Iteration;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\Models\HasPolicyContract;
use App\Models\BaseModelAbstract;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models\User
 * @property int $id
 * @property string $email the email address of the user
 * @property string $name the full name of the user
 * @property string $password the password of the user
 * @property string|null $stripe_customer_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wiki\Article[] $createdArticles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wiki\Iteration[] $createdIterations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Message[] $messages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment\PaymentMethod[] $paymentMethods
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Subscription\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeCustomerKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends BaseModelAbstract
    implements AuthenticatableContract, JWTSubject, HasPolicyContract, HasValidationRulesContract
{
    use Authenticatable, HasValidationRules;

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
    public function createdArticles(): HasMany
    {
        return $this->hasMany(Article::class, 'created_by_id');
    }

    /**
     * The iterations that were created by this user
     *
     * @return HasMany
     */
    public function createdIterations(): HasMany
    {
        return $this->hasMany(Iteration::class, 'created_by_id');
    }

    /**
     * The messages that were sent to a user
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * A user can have many payment methods
     *
     * @return HasMany
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * What roles this user has
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * All Subscriptions this user has signed up to
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Add a Role to this user
     *
     * @param int $roleId
     * @return $this
     */
    public function addRole(int $roleId)
    {
        $this->roles()->attach($roleId);
        return $this;
    }

    /**
     * Does this have the role
     *
     * @param mixed $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $roles = (array)$roles;
        return $this->roles()->whereIn('id', $roles)->exists();
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
     * Build the model validation rules
     * @param array $params
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        $emailUnique = Rule::unique('users', 'email');

        $userId = count($params) ? $params[0]->id : null;

        if ($userId) {
            $emailUnique->ignore($userId);
        }

        return [
            static::VALIDATION_RULES_BASE => [
                'email' => [
                    'string',
                    'max:120',
                    'email',
                    $emailUnique,
                ],
                'name' => [
                    'string',
                    'max:120',
                ],
                'password' => [
                    'string',
                    'min:6',
                ],
            ],
        ];
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
     *         property="roles",
     *         description="The roles that this user has.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Role")
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
