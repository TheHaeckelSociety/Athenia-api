<?php
declare(strict_types=1);

namespace App\Models\User;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ProfileImage
 *
 * @package App\Models\User
 * @property int $id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conference\Event[] $events
 * @property-read \App\Models\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereUrl($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conference\Conference[] $conferences
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\ProfileImage whereName($value)
 */
class ProfileImage extends Asset
{
    /**
     * @return HasOne
     */
    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }
}