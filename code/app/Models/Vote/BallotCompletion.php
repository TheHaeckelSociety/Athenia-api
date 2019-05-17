<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BallotCompletion
 * @package App\Models\Vote
 */
class BallotCompletion extends BaseModelAbstract
{
    /**
     * The ballot that was completed
     *
     * @return BelongsTo
     */
    public function ballot(): BelongsTo
    {
        return $this->belongsTo(Ballot::class);
    }

    /**
     * THe user that completed this ballot
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All votes that were cat in this ballot
     *
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}