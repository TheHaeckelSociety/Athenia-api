<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Payment;

use App\Models\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * Class PaymentMethodTest
 * @package Tests\Unit\Models\Payment
 */
class PaymentMethodTest extends TestCase
{
    public function testUser()
    {
        $model = new PaymentMethod();
        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('payment_methods.user_id', $relation->getQualifiedForeignKeyName());
    }
}