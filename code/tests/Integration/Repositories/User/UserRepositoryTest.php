<?php
declare(strict_types=1);

namespace Tests\Integration\Repositories\User;

use App\Exceptions\NotImplementedException;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class UserRepositoryTest
 * @package Tests\Integration\Repositories\User
 */
class UserRepositoryTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var UserRepository
     */
    protected $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();

        $this->repository = new UserRepository(new User(), $this->getGenericLogMock());
    }

    public function testFindAllThrowsException()
    {
        $this->expectException(NotImplementedException::class);

        $this->repository->findAll();
    }

    public function testFindOrFailThrowsException()
    {
        $this->expectException(NotImplementedException::class);

        $this->repository->findOrFail(5);
    }

    public function testCreateSuccess()
    {
        $user = $this->repository->create([
            'email' => 'test@test.com',
            'name' => 'Kelly Ann Conway',
            'password' => 'Something secure',
        ]);

        $this->assertEquals(1, User::count());
        $this->assertEquals('test@test.com', $user->email);
        $this->assertEquals('Kelly Ann Conway', $user->name);
        $this->assertEquals('Something secure', $user->password);
    }

    public function testUpdateSuccess()
    {
        $model = factory(User::class)->create(['email' => 'butts@butts.com']);
        $this->repository->update($model, ['email' => 'bump@butts.com']);

        $updated = User::find($model->id);
        $this->assertEquals('bump@butts.com', $updated->email);
    }

    public function testDeleteThrowsException()
    {
        $this->expectException(NotImplementedException::class);

        $this->repository->delete(new User());
    }

    public function testFindByEmailSuccess()
    {
        $user = factory(User::class)->create([
            'email' => 'test@test.com',
        ]);

        $result = $this->repository->findByEmail('test@test.com');

        $this->assertEquals($user->id, $result->id);
    }

    public function testFindByEmailReturnsNull()
    {
        $this->assertNull($this->repository->findByEmail('test@test.com'));
    }
}