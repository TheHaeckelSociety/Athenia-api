<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class BallotSubject
 *
 * @package App\Models\Vote
 * @property int $id
 * @property int $ballot_id
 * @property int $subject_id
 * @property string $subject_type
 * @property int $votes_cast
 * @property int $vote_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \App\Models\Vote\Ballot $ballot
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote\BallotSubject[] $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote\Vote[] $votes
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotSubject newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotSubject newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereBallotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereVoteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotSubject whereVotesCast($value)
 * @mixin \Eloquent
 */
class BallotSubject extends BaseModelAbstract
{
    /**
     * The ballot this is apart of
     *
     * @return BelongsTo
     */
    public function ballot(): BelongsTo
    {
        return $this->belongsTo(Ballot::class);
    }

    /**
     * The subject that this represents
     *
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The votes that have been submitted for this ballot subject
     *
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}