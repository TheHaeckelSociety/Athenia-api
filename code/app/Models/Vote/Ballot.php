<?php
declare(strict_types=1);

namespace App\Models\Vote;

use App\Models\BaseModelAbstract;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Ballot
 * @package App\Models\Vote
 */
class Ballot extends BaseModelAbstract
{
    /**
     * This ballot type when there is a single subject and the user chooses yes or no
     */
    const TYPE_SINGLE_OPTION = 'single_option';

    /**
     * All times someone has completed this ballot
     *
     * @return HasMany
     */
    public function ballotCompletions(): HasMany
    {
        return $this->hasMany(BallotCompletion::class);
    }

    /**
     * All subjects contained in this ballot
     *
     * @return HasMany
     */
    public function ballotSubjects(): HasMany
    {
        return $this->hasMany(BallotSubject::class);
    }
}