<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Models\Asset;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $conferences_count
 * @property-read int|null $events_count
 * @property-read User $user
 * @method static Builder|ProfileImage newModelQuery()
 * @method static Builder|ProfileImage newQuery()
 * @method static Builder|ProfileImage query()
 * @method static Builder|ProfileImage whereCaption($value)
 * @method static Builder|ProfileImage whereCreatedAt($value)
 * @method static Builder|ProfileImage whereDeletedAt($value)
 * @method static Builder|ProfileImage whereId($value)
 * @method static Builder|ProfileImage whereName($value)
 * @method static Builder|ProfileImage whereUpdatedAt($value)
 * @method static Builder|ProfileImage whereUrl($value)
 * @mixin Eloquent
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read int|null $contents_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereUserId($value)
 */
class ProfileImage extends Asset
{
    /**
     * @return HasOne
     */

}
