<?php
declare(strict_types=1);

namespace App\Listeners\Message;

use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Events\Message\MessageCreatedEvent;
use App\Mail\MessageMailer;
use Carbon\Carbon;
use Illuminate\Contracts\Mail\Mailer;

/**
 * Class MessageCreatedListener
 * @package App\Listeners\Message
 */
class MessageCreatedListener
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var MessageRepositoryContract
     */
    private $messageRepository;

    /**
     * MessageCreatedListener constructor.
     * @param Mailer $mailer
     * @param MessageRepositoryContract $messageRepository
     */
    public function __construct(Mailer $mailer, MessageRepositoryContract $messageRepository)
    {
        $this->mailer = $mailer;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Schedules the message to be sent
     *
     * @param MessageCreatedEvent $event
     */
    public function handle(MessageCreatedEvent $event)
    {
        $message = $event->getMessage();

        $this->messageRepository->update($message, [
            'scheduled_at' => Carbon::now(),
        ]);
        $this->mailer->send(new MessageMailer($message));
    }
}