<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Contracts\Models\HasPolicyContract;
use App\Contracts\Models\HasValidationRulesContract;
use App\Models\Traits\HasValidationRules;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\Message\MessageCreatedEvent;
use App\Models\BaseModelAbstract;

/**
 * Class Message
 *
 * @package App\Models\User
 * @property int $id
 * @property string $subject
 * @property string $template
 * @property mixed $data
 * @property string $email
 * @property int $to_id
 * @property int $from_id
 * @property int|null $thread_id
 * @property array $via
 * @property string|null $action
 * @property \Carbon\Carbon|null $scheduled_at
 * @property \Carbon\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $seen_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User\User $to
 * @property-read \App\Models\User\User $from
 * @property-read \App\Models\User\Thread|null $thread
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereVia($value)
 * @mixin \Eloquent
 */
class Message extends BaseModelAbstract
{
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
     * Makes sure to default the order of this table to the order field
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

    /**
     * Swagger definition below...
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="Message",
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
     *         property="template",
     *         type="string",
     *         maxLength=32,
     *         description="The template for this message",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="email",
     *         type="string",
     *         maxLength=120,
     *         description="The email address that this message was sent to",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="subject",
     *         type="string",
     *         maxLength=256,
     *         description="The subject of the email that was sent",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="data",
     *         type="object",
     *         description="A JSON object of the data used to fill the template",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="scheduled_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of when this message was put into the queue",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="sent_at",
     *         type="string",
     *         format="date-time",
     *         description="UTC date of when this message was sent to the user",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="user_id",
     *         type="integer",
     *         format="int32",
     *         description="The primary id of the user that this was sent to",
     *         readOnly=true
     *     ),
     *     @SWG\Property(
     *         property="user",
     *         description="The users that this was sent to.",
     *         type="array",
     *         @SWG\Items(ref="#/definitions/User")
     *     )
     * )
     */
}