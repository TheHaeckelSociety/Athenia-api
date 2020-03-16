<?php
declare(strict_types=1);

namespace App\Models\Payment;

use App\Models\BaseModelAbstract;
use App\Models\Subscription\Subscription;
use Eloquent;
use Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Class PurchasedItem
 *
 * @package App\Models\Payment
 * @property int $id
 * @property int $payment_id
 * @property int $item_id
 * @property string $item_type
 * @property float $amount
 * @property Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read Subscription|Model|Eloquent $item
 * @property-read Payment $payment
 * @method static EloquentJoinBuilder|PurchasedItem newModelQuery()
 * @method static EloquentJoinBuilder|PurchasedItem newQuery()
 * @method static EloquentJoinBuilder|PurchasedItem query()
 * @method static Builder|PurchasedItem whereAmount($value)
 * @method static Builder|PurchasedItem whereCreatedAt($value)
 * @method static Builder|PurchasedItem whereDeletedAt($value)
 * @method static Builder|PurchasedItem whereId($value)
 * @method static Builder|PurchasedItem whereItemId($value)
 * @method static Builder|PurchasedItem whereItemType($value)
 * @method static Builder|PurchasedItem wherePaymentId($value)
 * @method static Builder|PurchasedItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PurchasedItem extends BaseModelAbstract
{
    /**
     * The item that was purchased
     *
     * @return MorphTo
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The payment this purchased item is related to
     *
     * @return BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}