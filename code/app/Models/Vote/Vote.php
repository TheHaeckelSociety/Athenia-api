<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Vote
 * @package App\Models\Vote
 */
class Vote extends BaseModelAbstract
{
    /**
     * The ballot completion that this is part of
     *
     * @return BelongsTo
     */
    public function ballotCompletion(): BelongsTo
    {
        return $this->belongsTo(BallotCompletion::class);
    }

    /**
     * The subject that was voted for
     *
     * @return BelongsTo
     */
    public function ballotSubject(): BelongsTo
    {
        return $this->belongsTo(BallotSubject::class);
    }
}