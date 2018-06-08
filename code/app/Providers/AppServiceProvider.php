<?php
declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\TokenGenerationServiceContract;
use App\Services\TokenGenerationService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @return array
     */
    public function provides()
    {
        return [
            TokenGenerationServiceContract::class,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEnvironmentSpecificProviders();
    }

    /**
     * Registers any environment specific rpviders
     */
    public function registerEnvironmentSpecificProviders()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
        $this->app->bind(TokenGenerationServiceContract::class, function() {
            return new TokenGenerationService();
        });
    }
}
