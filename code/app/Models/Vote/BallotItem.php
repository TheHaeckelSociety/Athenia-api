<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Eloquent;
use Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class BallotSubject
 *
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
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote\Vote[] $votes
 * @property-read int|null $votes_count
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotItem newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotItem newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|\App\Models\Vote\BallotItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereBallotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereVoteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vote\BallotItem whereVotesCast($value)
 * @mixin \Eloquent
 */
class BallotItem extends BaseModelAbstract
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
