<?php
declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\User\MessageRepositoryContract;
use App\Contracts\Repositories\User\PasswordTokenRepositoryContract;
use App\Contracts\Repositories\Wiki\ArticleRepositoryContract;
use App\Contracts\Services\TokenGenerationServiceContract;
use App\Models\User\Message;
use App\Models\User\PasswordToken;
use App\Models\Wiki\Article;
use App\Repositories\User\MessageRepository;
use App\Repositories\User\PasswordTokenRepository;
use App\Repositories\Wiki\ArticleRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
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
            ArticleRepositoryContract::class,
            MessageRepositoryContract::class,
            PasswordTokenRepositoryContract::class,
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
        $this->app->bind(ArticleRepositoryContract::class, function() {
            return new ArticleRepository(new Article(), $this->app->make('log'));
        });
        $this->app->bind(MessageRepositoryContract::class, function() {
            return new MessageRepository(new Message(), $this->app->make('log'));
        });
        $this->app->bind(PasswordTokenRepositoryContract::class, function() {
            return new PasswordTokenRepository(
                new PasswordToken(),
                $this->app->make('log'),
                $this->app->make(Dispatcher::class),
                $this->app->make(TokenGenerationServiceContract::class)
            );
        });
        $this->app->bind(UserRepositoryContract::class, function() {
            return new UserRepository(
                new User(),
                $this->app->make('log'),
                $this->app->make(Hasher::class)
            );
        });
    }
}