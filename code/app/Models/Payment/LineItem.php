<?php
declare(strict_types=1);

namespace App\Models\Payment;

use App\Models\BaseModelAbstract;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class PurchasedItem
 *
 * @property int $id
 * @property int $payment_id
 * @property int|null $item_id
 * @property string $item_type
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $item
 * @property-read \App\Models\Payment\Payment $payment
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|LineItem newModelQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|LineItem newQuery()
 * @method static \Fico7489\Laravel\EloquentJoin\EloquentJoinBuilder|LineItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LineItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LineItem extends BaseModelAbstract
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