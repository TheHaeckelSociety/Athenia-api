<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\Wiki\Article;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $modelPlaceHolders = [
        'article' => Article::class,
    ];

    /**
     * @var Router
     */
    private $router;

    /**
     * RouteServiceProvider constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->router = $app->make(Router::class);
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        foreach($this->modelPlaceHolders as $placeHolder => $model) {
            $this->router->pattern($placeHolder, '^[0-9]+$');
            $this->router->model($placeHolder, $model);
        }

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->router->middleware('api-v1')
            ->namespace('App\Http\V1\Controllers')
            ->group(base_path('routes/api-v1.php'));
    }
}
