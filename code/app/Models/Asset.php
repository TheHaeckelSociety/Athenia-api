<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\Models\HasValidationRulesContract;
use App\Models\Traits\HasValidationRules;
use App\Models\User\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

/**
 * Class Asset
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
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Asset newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Asset newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|Asset query()
 * @method static Builder|Asset whereCaption($value)
 * @method static Builder|Asset whereCreatedAt($value)
 * @method static Builder|Asset whereDeletedAt($value)
 * @method static Builder|Asset whereId($value)
 * @method static Builder|Asset whereName($value)
 * @method static Builder|Asset whereOwnerId($value)
 * @method static Builder|Asset whereOwnerType($value)
 * @method static Builder|Asset whereUpdatedAt($value)
 * @method static Builder|Asset whereUrl($value)
 * @mixin Eloquent
 */
class Asset extends BaseModelAbstract implements HasValidationRulesContract
{
    use HasValidationRules;

    /**
     * @var string Makes sure to override all children
     */
    protected $table = 'assets';

    /**
     * The user that created this asset
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * All mime types that can be uploaded to the server for this asset
     *
     * @return array
     */
    protected function getAvailableMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
        ];
    }

    /**
     * Build the model validation rules
     * @param array $params
     * @return array
     */
    public function buildModelValidationRules(...$params): array
    {
        return [
            self::VALIDATION_RULES_BASE => [
                'file_contents' => [
                    'string',
                ],

                'name' => [
                    'nullable',
                    'string',
                ],

                'caption' => [
                    'nullable',
                    'string',
                ],

                // Set in the request object, and not set from the user request
                'mime_type' => [
                    Rule::in($this->getAvailableMimeTypes()),
                ],
            ],
            self::VALIDATION_RULES_CREATE => [
                self::VALIDATION_PREPEND_REQUIRED => [
                    'file_contents',
                ],
            ],
            self::VALIDATION_RULES_UPDATE => [
                self::VALIDATION_PREPEND_NOT_PRESENT => [
                    'file_contents',
                ],
            ]
        ];
    }
}
