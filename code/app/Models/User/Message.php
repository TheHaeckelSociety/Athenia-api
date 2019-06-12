<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Events\Message\MessageCreatedEvent;
use App\Models\BaseModelAbstract;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Message
 *
 * @package App\Models\User
 * @property int $id
 * @property string $subject
 * @property string $template
 * @property mixed $data
 * @property string $email
 * @property int $user_id
 * @property Carbon|null $scheduled_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereData($value)
 * @method static Builder|Message whereDeletedAt($value)
 * @method static Builder|Message whereEmail($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereScheduledAt($value)
 * @method static Builder|Message whereSentAt($value)
 * @method static Builder|Message whereSubject($value)
 * @method static Builder|Message whereTemplate($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @method static Builder|Message whereUserId($value)
 * @mixin Eloquent
 */
class Message extends BaseModelAbstract
{
    /**
     * @var array
     */
    protected $dates = [
        'sent_at',
        'scheduled_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data' => 'array'
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
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