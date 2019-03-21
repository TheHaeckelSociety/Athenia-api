<?php
declare(strict_types=1);

namespace Tests\Unit\Models\User;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User\Message;
use Tests\TestCase;

/**
 * Class MessageTest
 * @package Tests\Unit\Models\User
 */
class MessageTest extends TestCase
{
    public function testUser()
    {
        $message = new Message();
        $relation = $message->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('users.id', $relation->getQualifiedOwnerKeyName());
        $this->assertEquals('messages.user_id', $relation->getQualifiedForeignKeyName());
    }
}