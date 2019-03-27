<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\Repositories\Payment\PaymentMethodRepositoryContract;
use App\Contracts\Repositories\User\UserRepositoryContract;
use App\Contracts\Services\StripeCustomerServiceContract;
use App\Models\Payment\PaymentMethod;
use App\Models\User\User;
use Cartalyst\Stripe\Api\Cards;
use Cartalyst\Stripe\Api\Customers;
use InvalidArgumentException;

/**
 * Class StripeCustomerService
 * @package App\Services
 */
class StripeCustomerService implements StripeCustomerServiceContract
{
    /**
     * @var UserRepositoryContract
     */
    private $userRepository;

    /**
     * @var PaymentMethodRepositoryContract
     */
    private $paymentMethodRepository;

    /**
     * @var Customers
     */
    private $customerHelper;

    /**
     * @var Cards
     */
    private $cardHelper;

    /**
     * StripeCustomerService constructor.
     * @param UserRepositoryContract $userRepository
     * @param PaymentMethodRepositoryContract $paymentMethodRepository
     * @param Customers $customerHelper
     * @param Cards $cardHelper
     */
    public function __construct(UserRepositoryContract $userRepository,
                                PaymentMethodRepositoryContract $paymentMethodRepository,
                                Customers $customerHelper, Cards $cardHelper)
    {
        $this->userRepository = $userRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->customerHelper = $customerHelper;
        $this->cardHelper = $cardHelper;
    }

    /**
     * Creates a new stripe customer for a user
     *
     * @param User $user
     * @return mixed
     */
    public function createCustomer(User $user)
    {
        $data = $this->customerHelper->create([
            'email' => $user->email,
        ]);

        $this->userRepository->update($user, [
            'stripe_customer_key' => $data['id'],
        ]);

        $user->stripe_customer_key = $data['id'];

        return $data;
    }

    /**
     * Retrieves a customer from stripe
     *
     * @param User $user
     * @return mixed
     */
    public function retrieveCustomer(User $user)
    {
        if (!$user->stripe_customer_key) {
            throw new InvalidArgumentException('The passed in user does not have a stripe customer key associated with their account.');
        }

        return $this->customerHelper->find($user->stripe_customer_key);
    }

    /**
     * Creates a new payment method
     *
     * @param User $user
     * @param array $paymentData
     * @return mixed
     */
    public function createPaymentMethod(User $user, array $paymentData): PaymentMethod
    {
        if (!$user->stripe_customer_key) {
            $this->createCustomer($user);
        }

        $data = $this->cardHelper->create($user->stripe_customer_key, $paymentData);

        return $this->paymentMethodRepository->create([
            'payment_method_key' => $data['id'],
            'payment_method_type' => 'stripe',
        ], $user);
    }

    /**
     * Interacts with stripe in order to properly delete a user's card
     *
     * @param PaymentMethod $paymentMethod
     * @return mixed
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if (!$paymentMethod->user->stripe_customer_key) {
            throw new InvalidArgumentException('The passed in user does not have a stripe customer key associated with their account.');
        }

        return $this->cardHelper->delete($paymentMethod->user->stripe_customer_key, $paymentMethod->payment_method_key);
    }

    /**
     * Interacts with stripe in order to properly retrieve information on a card
     *
     * @param PaymentMethod $paymentMethod
     * @return mixed
     */
    public function retrievePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user->stripe_customer_key) {
            return $this->cardHelper->find($paymentMethod->user->stripe_customer_key, $paymentMethod->payment_method_key);
        }

        return null;
    }
}