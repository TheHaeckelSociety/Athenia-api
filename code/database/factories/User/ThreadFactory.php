<?php
declare(strict_types=1);

namespace Database\Factories\User;

use App\Models\User\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ThreadFactory
 * @package Database\Factories\User
 */
class ThreadFactory extends Factory
{
    /**
     * @var string The related model
     */
    protected $model = Thread::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [];
    }
}
