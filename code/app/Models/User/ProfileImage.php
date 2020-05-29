<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Contracts\Models\BelongsToOrganizationContract;
use App\Models\Asset;
use App\Models\Organization\Organization;
use App\Models\Traits\BelongsToOrganization;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class ProfileImage
 *
 * @package App\Models\User
 * @property int $id
 * @property string $url
 * @property string|null $caption
 * @property string|null $name
 * @property int|null $owner_id
 * @property string|null $owner_type
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|Organization|Model|Eloquent $owner
 * @property-read int|null $conferences_count
 * @property-read int|null $events_count
 * @method static Builder|ProfileImage newModelQuery()
 * @method static Builder|ProfileImage newQuery()
 * @method static Builder|ProfileImage query()
 * @method static Builder|ProfileImage whereCaption($value)
 * @method static Builder|ProfileImage whereCreatedAt($value)
 * @method static Builder|ProfileImage whereDeletedAt($value)
 * @method static Builder|ProfileImage whereId($value)
 * @method static Builder|ProfileImage whereName($value)
 * @method static Builder|ProfileImage whereOwnerId($value)
 * @method static Builder|ProfileImage whereOwnerType($value)
 * @method static Builder|ProfileImage whereUpdatedAt($value)
 * @method static Builder|ProfileImage whereUrl($value)
 * @mixin Eloquent
 */
class ProfileImage extends Asset
{
    /**
     * @return HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
