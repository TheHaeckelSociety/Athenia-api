<?php
declare(strict_types=1);

namespace App\Http\Core\Requests\Entity\PaymentMethod;

use App\Contracts\Http\HasEntityInRequestContract;
use App\Http\Core\Requests\BaseAuthenticatedRequestAbstract;
use App\Http\Core\Requests\Entity\Traits\IsEntityRequestTrait;
use App\Http\Core\Requests\Traits\HasNoExpands;
use App\Models\Payment\PaymentMethod;
use App\Models\Subscription\Subscription;
use App\Policies\Payment\PaymentMethodPolicy;
use App\Policies\Subscription\SubscriptionPolicy;

/**
 * Class UpdateRequest
 * @package App\Http\Core\Requests\Entity\PaymentMethod
 */
class UpdateRequest extends BaseAuthenticatedRequestAbstract implements HasEntityInRequestContract
{
    use HasNoExpands, IsEntityRequestTrait;

    /**
     * Get the policy action for the guard
     *
     * @return string
     */
    protected function getPolicyAction(): string
    {
        return PaymentMethodPolicy::ACTION_UPDATE;
    }

    /**
     * Get the class name of the policy that this request utilizes
     *
     * @return string
     */
    protected function getPolicyModel(): string
    {
        return PaymentMethod::class;
    }

    /**
     * Gets any additional parameters needed for the policy function
     *
     * @return array
     */
    protected function getPolicyParameters(): array
    {
        return [
            $this->getEntity(),
            $this->route('payment_method'),
        ];
    }

    /**
     * Get validation rules for the create request
     *
     * @param PaymentMethod $paymentMethod
     * @return array
     */
    public function rules(PaymentMethod $paymentMethod) : array
    {
        return $paymentMethod->getValidationRules(PaymentMethod::VALIDATION_RULES_UPDATE);
    }
}
