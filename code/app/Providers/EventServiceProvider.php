<?php
declare(strict_types=1);

namespace App\Providers;

use App\Events\Message\MessageCreatedEvent;
use App\Events\Message\MessageSentEvent;
use App\Events\User\SignUpEvent;
use App\Listeners\Message\MessageCreatedListener;
use App\Listeners\Message\MessageSentListener;
use App\Listeners\User\SignUpListener;
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
        MessageCreatedEvent::class => [
            MessageCreatedListener::class,
        ],
        MessageSentEvent::class => [
            MessageSentListener::class,
        ],
        SignUpEvent::class => [
            SignUpListener::class,
        ],
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
