<?php
declare(strict_types=1);

namespace App\Providers;

use App\Events\Message\MessageCreatedEvent;
use App\Events\Message\MessageSentEvent;
use App\Events\Payment\PaymentReversedEvent;
use App\Events\User\ForgotPasswordEvent;
use App\Events\User\SignUpEvent;
use App\Events\Vote\VoteCreatedEvent;
use App\Listeners\Message\MessageCreatedListener;
use App\Listeners\Message\MessageSentListener;
use App\Listeners\User\ForgotPasswordListener;
use App\Listeners\User\SignUpListener;
use App\Listeners\Vote\VoteCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ForgotPasswordEvent::class => [
            ForgotPasswordListener::class,
        ],
        MessageCreatedEvent::class => [
            MessageCreatedListener::class,
        ],
        MessageSentEvent::class => [
            MessageSentListener::class,
        ],
        PaymentReversedEvent::class => [

        ],
        SignUpEvent::class => [
            SignUpListener::class,
        ],
        VoteCreatedEvent::class => [
            VoteCreatedListener::class,
        ],

        // Register all application level events below
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
