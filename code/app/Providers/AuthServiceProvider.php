<?php
declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\User\UserRepositoryContract;
use App\Models\User\User;
use App\Models\Wiki\Article;
use App\Policies\ArticlePolicy;
use App\Policies\UserPolicy;
use App\Services\UserAuthenticationService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** @var AuthManager $auth */
        $auth = $this->app->make('auth');

        $auth->provider('user-authentication', function ($app, array $config) {

            /** @var Application $app */
            $userRepository = $app->make(UserRepositoryContract::class);
            $hasher = $app->make(Hasher::class);

            return new UserAuthenticationService($userRepository, $hasher);
        });
    }
}
