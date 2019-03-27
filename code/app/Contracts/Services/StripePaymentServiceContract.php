<?php
declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Payment\Payment;
use App\Models\Payment\PaymentMethod;
use App\Models\User\User;
use Exception;

/**
 * Interface StripePaymentServiceContract
 * @package App\Contracts\Services
 */
interface StripePaymentServiceContract
{
    /**
     * @param float $amount
     * @param PaymentMethod $paymentMethod
     * @param string|null $customerKey
     * @return array
     */
    public function captureCharge(float $amount, PaymentMethod $paymentMethod, string $customerKey = null);

    /**
     * @param User $user
     * @param float $amount
     * @param PaymentMethod $paymentMethod
     * @param array $paymentData
     * @return mixed
     */
    public function createPayment(User $user, float $amount, PaymentMethod $paymentMethod, $paymentData = []) : Payment;

    /**
     * Reverses a payment, and then triggers an accompanying PaymentReversed Event
     *
     * @param Payment $payment
     * @return mixed
     */
    public function reversePayment(Payment $payment);
}