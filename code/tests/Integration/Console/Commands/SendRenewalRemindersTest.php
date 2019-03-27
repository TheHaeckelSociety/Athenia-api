<?php
declare(strict_types=1);

namespace Tests\Integration\Console\Commands;

use App\Console\Commands\SendRenewalReminders;
use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Models\Subscription\MembershipPlanRate;
use App\Models\Subscription\Subscription;
use App\Repositories\Subscription\SubscriptionRepository;
use Carbon\Carbon;
use Tests\CustomMockInterface;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;

/**
 * Class SendRenewalRemindersTest
 * @package Tests\Integration\Console\Commands
 */
class SendRenewalRemindersTest extends TestCase
{
    use DatabaseSetupTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
    }

    public function testHandle()
    {
        $subscriptionRepository = new SubscriptionRepository(
            new Subscription(),
            $this->getGenericLogMock()
        );
        /** @var MessageRepositoryContract|CustomMockInterface $messageRepository */
        $messageRepository = mock(MessageRepositoryContract::class);

        $command = new SendRenewalReminders($subscriptionRepository, $messageRepository);

        /** @var Subscription $subscription */
        $subscription = factory(Subscription::class)->create([
            'expires_at' => Carbon::now()->addWeek(2),
            'membership_plan_rate_id' => factory(MembershipPlanRate::class)->create()->id,
        ]);
        factory(Subscription::class)->create([
            'expires_at' => Carbon::now()->addWeek()->addDay(6),
            'membership_plan_rate_id' => factory(MembershipPlanRate::class)->create()->id,
        ]);
        factory(Subscription::class)->create([
            'expires_at' => Carbon::now()->addWeek(2)->addDay(1),
            'membership_plan_rate_id' => factory(MembershipPlanRate::class)->create()->id,
        ]);

        $messageRepository->shouldReceive('create')->once()->with(\Mockery::on(function($data) use($subscription) {

            $this->assertArrayHasKey('email', $data);
            $this->assertArrayHasKey('template', $data);
            $this->assertArrayHasKey('data', $data);
            $this->assertArrayHasKey('subject', $data);

            $this->assertArrayHasKey('greeting', $data['data']);
            $this->assertArrayHasKey('membership_name', $data['data']);
            $this->assertArrayHasKey('recurring', $data['data']);
            $this->assertArrayHasKey('membership_cost', $data['data']);

            $this->assertEquals($subscription->user->email, $data['email']);
            $this->assertContains($subscription->user->name, $data['data']['greeting']);

            return true;
        }), \Mockery::on(function($user) use($subscription) {
            $this->assertEquals($user, $subscription->user);
            return true;
        }));

        $command->handle();
    }
}