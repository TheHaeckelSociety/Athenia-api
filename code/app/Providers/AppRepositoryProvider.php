<?php
declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Models\User\Message;
use App\Repositories\User\MessageRepository;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\User\UserRepositoryContract;
use App\Models\User\User;
use App\Repositories\User\UserRepository;

/**
 * Class AppRepositoryProvider
 * @package App\Providers
 */
class AppRepositoryProvider extends ServiceProvider
{
    /**
     * @return array Holds information on every contract that is provided with this provider
     */
    public function provides()
    {
        return [
            MessageRepositoryContract::class,
            UserRepositoryContract::class,
        ];
    }

    /**
     * Register the repositories.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MessageRepositoryContract::class, function() {
            return new MessageRepository(new Message(), $this->app->make('log'));
        });
        $this->app->bind(UserRepositoryContract::class, function() {
            return new UserRepository(new User(), $this->app->make('log'));
        });
    }
}