<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class BallotSubject
 * @package App\Models\Vote
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