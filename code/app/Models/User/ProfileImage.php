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
 * @property int $id
 * @property int|null $owner_id
 * @property string|null $name
 * @property string|null $caption
 * @property string $url
 * @property Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property string|null $owner_type
 * @property-read Organization|null $organization
 * @property-read Model|\Eloquent $owner
 * @property-read \App\Models\User\User|null $user
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|ProfileImage newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|ProfileImage newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|ProfileImage query()
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
