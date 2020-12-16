<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class BallotSubject
 *
 * @property int $id
 * @property int $ballot_id
 * @property int $votes_cast
 * @property int $vote_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \App\Models\Vote\Ballot $ballot
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vote\BallotItemOption[] $ballotItemOptions
 * @property-read int|null $ballot_item_options_count
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|BallotItem newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|BallotItem newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|BallotItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereBallotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereVoteCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BallotItem whereVotesCast($value)
 * @mixin Eloquent
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
    public function ballotItemOptions(): HasMany
    {
        return $this->hasMany(BallotItemOption::class);
    }
}
