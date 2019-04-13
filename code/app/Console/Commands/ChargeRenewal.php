<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\Repositories\Subscription\SubscriptionRepositoryContract;
use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Contracts\Services\StripePaymentServiceContract;
use App\Models\Subscription\Subscription;
use Carbon\Carbon;
use Cartalyst\Stripe\Exception\ApiLimitExceededException;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\NotFoundException;
use Exception;
use Illuminate\Console\Command;

/**
 * Class ChargeRenewal
 * @package App\Console\Commands
 */
class ChargeRenewal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge-renewal';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Charge Renewals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempts to charge all recurring renewals due today.';

    /**
     * @var MessageRepositoryContract
     */
    private $messageRepository;

    /**
     * @var StripePaymentServiceContract
     */
    private $paymentService;

    /**
     * @var SubscriptionRepositoryContract
     */
    private $subscriptionRepository;

    /**
     * ChargeRenewal constructor.
     * @param StripePaymentServiceContract $paymentService
     * @param SubscriptionRepositoryContract $subscriptionRepository
     * @param MessageRepositoryContract $messageRepository
     */
    public function __construct(StripePaymentServiceContract $paymentService, SubscriptionRepositoryContract $subscriptionRepository,
                                MessageRepositoryContract $messageRepository)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Charges a recurring subscription
     *
     * @param Subscription $subscription
     */
    public function chargeRecurring(Subscription $subscription)
    {
        if ($subscription->paymentMethod->payment_method_type == 'stripe') {
            $this->chargeStripe($subscription);
        } else {
            $this->checkPayPal($subscription);
        }
    }

    /**
     * Charges a payment to stripe, and handles the result
     *
     * @param Subscription $subscription
     */
    public function chargeStripe(Subscription $subscription)
    {
        try {
            $this->paymentService->createPayment($subscription->user, (float)$subscription->membershipPlanRate->cost, $subscription->paymentMethod, [
                'subscription_id' => $subscription->id,
            ]);
            $this->handleSuccess($subscription);

        } catch (NotFoundException $e) {
            $this->sendFailureEmail($subscription, 'Renewal card no longer on file.');
        } catch (CardErrorException $e) {
            $this->sendFailureEmail($subscription, $e->getMessage());
        } catch (ApiLimitExceededException $e) {
            $sleepTime = $this->getLaravel()->environment() == 'production' ? 60 : 0;
            $this->reattemptCharge($subscription, $sleepTime);
        } catch (Exception $e) {
            dd($e);
            $this->sendFailureEmail($subscription, 'Unknown Error');
        }
    }

    /**
     * Checks in with PayPal in order to see if a payment was renewed, and then sends an appropriate response to the user
     *
     * @param Subscription $subscription
     */
    public function checkPayPal(Subscription $subscription)
    {

    }

    /**
     * Sorts through all subscriptions expiring today, and then attempts to charge them.
     * Also sends the results to all users
     */
    public function handle()
    {
        /** @var Subscription $subscription */
        foreach ($this->subscriptionRepository->findExpiring(Carbon::now()) as $subscription) {
            if ($subscription->recurring) {
                $this->chargeRecurring($subscription);
            } else {
                $this->sendExpirationEmail($subscription);
            }
        }
    }

    /**
     * Updates the subscription and sends the result to the user
     *
     * @param Subscription $subscription
     */
    public function handleSuccess(Subscription $subscription)
    {
        /** @var Subscription $updatedSubscription */
        $updatedSubscription = $this->subscriptionRepository->update($subscription, [
            'last_renewed_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
        ]);
        $this->messageRepository->create([
            'subject' => 'Successfully Renewed',
            'template' => 'membership-renewed',
            'email' => $subscription->user->email,
            'data' => [
                'greeting' => 'Hello ' . $updatedSubscription->user->name,
                'membership_name' => $updatedSubscription->membershipPlanRate->membershipPlan->name,
                'membership_cost' => $updatedSubscription->formatted_cost,
                'expiration_date' => $updatedSubscription->formatted_expires_at . ' ' . $updatedSubscription->expires_at->format('Y'),
            ],
        ], $subscription->user);
    }

    /**
     * Sleeps a little bit, and then attempts to make the charge one more time
     *
     * @param Subscription $subscription
     * @param int $seconds
     */
    public function reattemptCharge(Subscription $subscription, int $seconds = 0)
    {
        sleep($seconds);
        $this->chargeStripe($subscription);
    }


    /**
     * Sends the expiration email to the user when the user chose to not have their membership auto renew
     *
     * @param Subscription $subscription
     */
    public function sendExpirationEmail(Subscription $subscription)
    {
        $this->messageRepository->create([
            'subject' => 'Expired',
            'template' => 'membership-expired',
            'email' => $subscription->user->email,
            'data' => [
                'greeting' => 'Hello ' . $subscription->user->name,
                'membership_name' => $subscription->membershipPlanRate->membershipPlan->name,
            ],
        ], $subscription->user);
    }

    /**
     * Sends an email to the user when a renewal has failed
     *
     * @param Subscription $subscription
     * @param string $reason
     */
    public function sendFailureEmail(Subscription $subscription, string $reason)
    {
        $this->messageRepository->create([
            'subject' => 'Renewal Failed',
            'template' => 'membership-renewal-failure',
            'email' => $subscription->user->email,
            'data' => [
                'greeting' => 'Hello ' . $subscription->user->name,
                'membership_name' => $subscription->membershipPlanRate->membershipPlan->name,
                'reason' => $reason,
            ],
        ], $subscription->user);
    }
}