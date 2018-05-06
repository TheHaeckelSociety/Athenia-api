<?php
declare(strict_types=1);

namespace Tests\Unit\Listeners\Message;

use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Events\Message\MessageCreatedEvent;
use App\Listeners\Message\MessageCreatedListener;
use App\Mail\MessageMailer;
use App\Models\User\Message;
use Carbon\Carbon;
use Illuminate\Contracts\Mail\Mailer;
use Tests\TestCase;

/**
 * Class MessageCreatedListenerTest
 * @package Tests\Unit\Listeners\Message
 */
class MessageCreatedListenerTest extends TestCase
{
    public function testHandle()
    {
        $mailer = mock(Mailer::class);
        $messageRepository = mock(MessageRepositoryContract::class);
        $listener = new MessageCreatedListener($mailer, $messageRepository);

        $message = new Message();
        $event = new MessageCreatedEvent($message);

        $carbon = new Carbon();
        Carbon::setTestNow($carbon);

        $messageRepository->shouldReceive('update')->once()->with($message, ['scheduled_at' => $carbon]);
        $mailer->shouldReceive('send')->once()->with(\Mockery::on(function (MessageMailer $mailer) {

            return true;
        }));

        $listener->handle($event);
    }
}