<?php
declare(strict_types=1);

namespace Tests\Unit\Models\Payment;

use App\Models\Payment\PaymentMethod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * Class PaymentMethodTest
 * @package Tests\Unit\Models\Payment
 */
class PaymentMethodTest extends TestCase
{
    public function testPayments()
    {
        $user = new PaymentMethod();
        $relation = $user->payments();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('payment_methods.id', $relation->getQualifiedParentKeyName());
        $this->assertEquals('payments.payment_method_id', $relation->getQualifiedForeignKeyName());
    }

    public function testUser()
    {
        $model = new PaymentMethod();
        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('payment_methods.user_id', $relation->getQualifiedForeignKeyName());
    }
}