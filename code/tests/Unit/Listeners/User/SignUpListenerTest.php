<?php
declare(strict_types=1);

namespace Tests\Unit\Listeners\User;

use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Events\Message\MessageCreatedEvent;
use App\Events\User\SignUpEvent;
use App\Listeners\User\SignUpListener;
use App\Models\User\Message;
use App\Models\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Tests\CustomMockInterface;
use Tests\TestCase;

/**
 * Class SignUpListenerTest
 * @package Tests\Unit\Listeners\User
 */
class SignUpListenerTest extends TestCase
{
    public function testHandle()
    {
        /** @var MessageRepositoryContract|CustomMockInterface $messageRepository */
        $repository = mock(MessageRepositoryContract::class);

        $user = new User([
            'name' => 'Ralph Nadar',
            'email' => 'test@test.com',
        ]);

        $repository->shouldReceive('create')->once()->with(\Mockery::on(function ($data) {
            $this->assertArrayHasKey('subject', $data);
            $this->assertArrayHasKey('email', $data);
            $this->assertArrayHasKey('template', $data);
            $this->assertEquals('sign-up', $data['template']);

            $this->assertArrayHasKey('data', $data);
            $this->assertArrayHasKey('greeting', $data['data']);

            return true;
        }), $user);

        /** @var Dispatcher|CustomMockInterface $dispatcher */
        $dispatcher = mock(Dispatcher::class);

        $messageCreatedEventDispatched = false;
        $dispatcher->shouldReceive('dispatch')->with(\Mockery::on(function ($event) use (&$messageCreatedEventDispatched) {
            if ($event instanceof MessageCreatedEvent) {
                $messageCreatedEventDispatched = true;
            }

            return true;
        }));

        Message::setEventDispatcher($dispatcher);

        $listener = new SignUpListener($repository);

        $event = new SignUpEvent($user);

        $listener->handle($event);
    }
}