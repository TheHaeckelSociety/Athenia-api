<?php
declare(strict_types=1);

namespace Tests\Integration\Repositories;

use App\Models\Feature;
use App\Repositories\FeatureRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\DatabaseSetupTrait;
use Tests\TestCase;
use Tests\Traits\MocksApplicationLog;

/**
 * Class ResourceRepositoryTest
 * @package Tests\Integration\Repositories
 */
class FeatureRepositoryTest extends TestCase
{
    use DatabaseSetupTrait, MocksApplicationLog;

    /**
     * @var FeatureRepository
     */
    protected FeatureRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();

        $this->repository = new FeatureRepository(
            new Feature(),
            $this->getGenericLogMock()
        );
    }

    public function testFindAllSuccess()
    {
        factory(Feature::class, 5)->create();
        $items = $this->repository->findAll();
        $this->assertCount(5, $items);
    }

    public function testFindAllEmpty()
    {
        $items = $this->repository->findAll();
        $this->assertEmpty($items);
    }

    public function testFindOrFailSuccess()
    {
        $model = factory(Feature::class)->create();

        $foundModel = $this->repository->findOrFail($model->id);
        $this->assertEquals($model->id, $foundModel->id);
    }

    public function testFindOrFailFails()
    {
        factory(Feature::class)->create(['id' => 19]);

        $this->expectException(ModelNotFoundException::class);
        $this->repository->findOrFail(20);
    }

    public function testCreateSuccess()
    {
        /** @var Feature $feature */
        $feature = $this->repository->create([
            'name' => 'A Feature',
        ]);

        $this->assertEquals('A Feature', $feature->name);
    }

    public function testUpdateSuccess()
    {
        $model = factory(Feature::class)->create([
            'name' => 'a code'
        ]);
        $this->repository->update($model, [
            'name' => 'the same',
        ]);

        $updated = Feature::find($model->id);
        $this->assertEquals('the same', $updated->name);
    }

    public function testDeleteSuccess()
    {
        $model = factory(Feature::class)->create();

        $this->repository->delete($model);

        $this->assertNull(Feature::find($model->id));
    }
}
