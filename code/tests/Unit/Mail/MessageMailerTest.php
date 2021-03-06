<?php
declare(strict_types=1);

namespace Tests\Unit\Mail;

use App\Mail\MessageMailer;
use App\Models\User\Message;
use App\Models\User\User;
use Tests\TestCase;

/**
 * Class MessageMailerTest
 * @package Tests\Unit\Mail
 */
class MessageMailerTest extends TestCase
{
    public function testBuild()
    {
        $user = new User([
            'first_name' => 'Darlene Dora',
        ]);
        $message = new Message([
            'to' => $user,
            'subject' => 'Test Message',
            'email' => 'darlene@test.com',
            'reply_to_email' => 'john@test.com',
            'template' => 'base',
            'data' => [
                'greeting' => 'Hello Darlene!',
            ],
        ]);

        $messageMailer = new MessageMailer($message);

        $builtMailer = $messageMailer->build();

        $this->assertEquals([['name' => 'Darlene Dora', 'address' => 'darlene@test.com']], $builtMailer->to);
        $this->assertEquals([['name' => 'Project Athenia', 'address' => 'thehaeckelsociety@gmail.com']], $builtMailer->from);
        $this->assertEquals([['name' => 'Project Athenia', 'address' => 'thehaeckelsociety@gmail.com']], $builtMailer->bcc);
        $this->assertEquals([['name' => null, 'address' => 'john@test.com']], $builtMailer->replyTo);

        $this->assertEquals('Test Message', $builtMailer->subject);
        $this->assertEquals('mailers.base', $builtMailer->view);
        $this->assertEquals(['greeting' => 'Hello Darlene!'], $builtMailer->viewData);
    }
}
