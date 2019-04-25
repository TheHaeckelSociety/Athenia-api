<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Models\HasValidationRulesContract;
use App\Models\BaseModelAbstract;
use App\Models\Traits\HasValidationRules;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

/**
 * Class Thread
 *
 * @package App\Models\User
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Message|null $last_message
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\Message[] $messages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Thread whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Thread extends BaseModelAbstract implements HasPolicyContract, HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * The url of the profile image
     *
     * @var array
     */
    protected $appends = [
        'last_message',
    ];

    /**
     * All messages in this thread
     *
     * @return HasMany
     */
    public function messages() : HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'dsc');
    }

    /**
     * All users that are in this thread
     *
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the URL for the profile image
     *
     * @return null|string
     */
    public function getLastMessageAttribute()
    {
        return $this->messages ? $this->messages->first() : null;
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
                'users' => [
                    'required',
                    'array',
                ],
                'users.*' => [
                    'integer',
                    Rule::exists('users', 'id'),
                ],
            ],
        ];
    }
}