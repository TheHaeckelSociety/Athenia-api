<?php
declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Payment\PaymentMethod;
use App\Models\User\User;

/**
 * Interface StripeCustomerServiceContract
 * @package App\Contracts\Services
 */
interface StripeCustomerServiceContract
{
    /**
     * Creates a new stripe customer for a user
     *
     * @param User $user
     * @return mixed
     */
    public function createCustomer(User $user);

    /**
     * Retrieves a customer from stripe
     *
     * @param User $user
     * @return mixed
     */
    public function retrieveCustomer(User $user);

    /**
     * Creates a new payment method
     *
     * @param User $user
     * @param array $paymentData
     * @return mixed
     */
    public function createPaymentMethod(User $user, $paymentData): PaymentMethod;

    /**
     * Interacts with stripe in order to properly delete a user's card
     *
     * @param PaymentMethod $paymentMethod
     * @return mixed
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod);

    /**
     * Interacts with stripe in order to properly retrieve information on a card
     *
     * @param PaymentMethod $paymentMethod
     * @return mixed
     */
    public function retrievePaymentMethod(PaymentMethod $paymentMethod);
}