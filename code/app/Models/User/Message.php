<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Models\HasValidationRulesContract;
use App\Models\Traits\HasValidationRules;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\Message\MessageCreatedEvent;
use App\Models\BaseModelAbstract;

/**
 * Class Message
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $subject
 * @property string|null $template
 * @property array $data
 * @property int|null $to_id
 * @property int|null $from_id
 * @property int|null $thread_id
 * @property array|null $via
 * @property string|null $action
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $seen_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\User|null $from
 * @property-read \App\Models\User\Thread|null $thread
 * @property-read \App\Models\User\User|null $to
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Message newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Message newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Message query()
 * @method static Builder|Message whereAction($value)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereData($value)
 * @method static Builder|Message whereDeletedAt($value)
 * @method static Builder|Message whereEmail($value)
 * @method static Builder|Message whereFromId($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereScheduledAt($value)
 * @method static Builder|Message whereSeenAt($value)
 * @method static Builder|Message whereSentAt($value)
 * @method static Builder|Message whereSubject($value)
 * @method static Builder|Message whereTemplate($value)
 * @method static Builder|Message whereThreadId($value)
 * @method static Builder|Message whereToId($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @method static Builder|Message whereVia($value)
 * @mixin Eloquent
 */
class Message extends BaseModelAbstract implements HasPolicyContract, HasValidationRulesContract
{
    use HasValidationRules;

    const VIA_EMAIL = 'email';
    const VIA_PUSH_NOTIFICATION = 'push';

    /**
     * @var array
     */
    protected $dates = [
        'seen_at',
        'sent_at',
        'scheduled_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'via' => 'array',
    ];

    /**
     * Array of events that need to be dispatched
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MessageCreatedEvent::class
    ];

    /**
     * Each message belongs to a user
     *
     * @return BelongsTo
     */
    public function from() : BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * The thread that this message is in
     *
     * @return BelongsTo
     */
    public function thread() : BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Each message belongs to a user
     *
     * @return BelongsTo
     */
    public function to() : BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
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
                'message' => [
                    'string',
                ],
                'seen' => [
                    'boolean',
                ]
            ],
            static::VALIDATION_RULES_CREATE => [
                static::VALIDATION_PREPEND_REQUIRED => [
                    'message',
                ],
            ],
            static::VALIDATION_RULES_UPDATE => [
                static::VALIDATION_PREPEND_NOT_PRESENT => [
                    'message',
                ],
            ],
        ];
    }
}