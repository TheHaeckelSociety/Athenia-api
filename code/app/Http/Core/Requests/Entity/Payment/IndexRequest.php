<?php
declare(strict_types=1);

namespace App\Http\Core\Requests\Entity\Payment;

use App\Contracts\Http\HasEntityInRequestContract;
use App\Http\Core\Requests\BaseAuthenticatedRequestAbstract;
use App\Http\Core\Requests\Entity\Traits\IsEntityRequestTrait;
use App\Http\Core\Requests\Traits\HasNoExpands;
use App\Http\Core\Requests\Traits\HasNoRules;
use App\Models\Payment\Payment;
use App\Policies\Payment\PaymentPolicy;

/**
 * Class IndexRequest
 * @package App\Http\Core\Requests\Entity\Payment
 */
class IndexRequest extends BaseAuthenticatedRequestAbstract implements HasEntityInRequestContract
{
    use HasNoRules, IsEntityRequestTrait, HasNoExpands;

    /**
     * Get the policy action for the guard
     *
     * @return string
     */
    protected function getPolicyAction(): string
    {
        return PaymentPolicy::ACTION_LIST;
    }

    /**
     * Get the class name of the policy that this request utilizes
     *
     * @return string
     */
    protected function getPolicyModel(): string
    {
        return Payment::class;
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
        ];
    }
}